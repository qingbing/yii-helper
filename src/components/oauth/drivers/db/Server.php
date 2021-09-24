<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\oauth\drivers\db;


use yii\db\Query;
use YiiHelper\models\oauth\OauthUser;
use Zf\Helper\Format;

/**
 * 驱动 : oauth-db 存储驱动
 *
 * Class Server
 * @package YiiHelper\components\oauth\drivers\db
 */
class Server extends \YiiHelper\components\oauth\drivers\Server
{
    /**
     * @var bool 周期内次数超限是否抛出异常
     */
    public $tokenTableName = '{{%oauth_access_token}}';

    /**
     * 保存访问 token
     *
     * @param OauthUser $oauthUser
     * @param string $accessToken
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function pushAccessToken(OauthUser $oauthUser, string $accessToken)
    {
        $dbData = [
            'system_code'  => $this->getSystemCode(),
            'uuid'         => $oauthUser->uuid,
            'access_token' => $accessToken,
            'expire_at'    => Format::datetime($this->nowTimestamp + $this->accessTokenTtl)
        ];
        return \Yii::$app->db->createCommand()
                ->insert($this->tokenTableName, $dbData)
                ->execute() > 0;
    }

    /**
     * 清理 $count 个访问 token
     *
     * @param OauthUser $oauthUser
     * @param int $count
     * @return mixed
     * @throws \yii\db\Exception
     */
    protected function popAccessToken(OauthUser $oauthUser, $count = 0)
    {
        if ($count < 1) {
            return true;
        }
        $ids = (new Query())
            ->select('id')
            ->from($this->tokenTableName)
            ->where("system_code=:system_code AND uuid=:uuid AND expire_at>=:expire_at", [
                ':system_code' => $this->getSystemCode(),
                ':uuid'        => $oauthUser->uuid,
                ':expire_at'   => $this->nowDatetime,
            ])
            ->orderBy('expire_at ASC, id ASC')
            ->limit($count)
            ->createCommand()
            ->queryColumn();
        if (0 === count($ids)) {
            return true;
        }
        return \Yii::$app->db->createCommand()->delete($this->tokenTableName, [
                'id' => $ids
            ])
                ->execute() > 0;
    }

    /**
     * 获取当前拥有的访问 token 数量
     *
     * @param OauthUser $oauthUser
     * @return int|string|null
     * @throws \yii\db\Exception
     */
    public function getCounts(OauthUser $oauthUser)
    {
        // 删除已经过期的token
        \Yii::$app->db->createCommand()
            ->delete($this->tokenTableName, 'system_code=:system_code AND uuid=:uuid AND expire_at<:expire_at', [
                ':system_code' => $this->getSystemCode(),
                ':uuid'        => $oauthUser->uuid,
                ':expire_at'   => $this->nowDatetime,
            ])
            ->execute();
        // 查询有效数据
        return (new Query())
            ->from($this->tokenTableName)
            ->where("system_code=:system_code AND uuid=:uuid AND expire_at>=:expire_at", [
                ':system_code' => $this->getSystemCode(),
                ':uuid'        => $oauthUser->uuid,
                ':expire_at'   => $this->nowDatetime,
            ])
            ->count();
    }

    /**
     * 判断是否是有效token
     *
     * @param string $uuid
     * @param string $accessToken
     * @return bool
     */
    public function isExpireAccessToken($uuid, string $accessToken)
    {
        return (new Query())
                ->from($this->tokenTableName)
                ->where('system_code=:system_code AND uuid=:uuid AND access_token=:access_token AND expire_at>=:expire_at', [
                    ':system_code'  => $this->getSystemCode(),
                    ':uuid'         => $uuid,
                    ':access_token' => $accessToken,
                    ':expire_at'    => $this->nowDatetime,
                ])
                ->count() > 0;
    }
}