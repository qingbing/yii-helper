<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\replaceSetting\tools;


use yii\db\Query;
use YiiHelper\models\replaceSetting\ReplaceSetting as ReplaceSettingAlias;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 工具 ： 替换配置
 *
 * Class ReplaceSetting
 * @package YiiHelper\features\replaceSetting\tools
 */
class ReplaceSetting
{
    /**
     * 获取实例
     *
     * @param string $code
     * @return $this
     * @throws BusinessException
     */
    public static function getInstance(string $code)
    {
        return new self($code);
    }

    /**
     * @var array 模版记录
     */
    protected $record;

    /**
     * 获取模版实例
     *
     * ReplaceSetting constructor.
     * @param string $code
     * @throws BusinessException
     */
    final private function __construct(string $code)
    {
        $record = (new Query())
            ->from('pub_replace_setting')
            ->select(['template', 'content', 'replace_fields'])
            ->andWhere([
                'code' => $code,
            ])->one();
        if (false === $record) {
            throw new BusinessException(replace('找不到替换模版"{code}"', [
                '{code}' => $code,
            ]), 1000);
        }
        $this->record = $record;
    }

    /**
     * 获取最终替换的文本
     *
     * @param array $replaces
     * @return string
     */
    public function getContent(array $replaces = []): string
    {
        $replaces = array_merge(ReplaceSettingAlias::getReplaces(), $replaces);
        $template = empty($this->record['content']) ? $this->record['template'] : $this->record['content'];
        return replace($template, $replaces, true);
    }
}