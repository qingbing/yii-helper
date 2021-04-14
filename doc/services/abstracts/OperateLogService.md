# 服务 OperateLogService ： 操作日志服务
- 设置日志模型 ： setModel(OperateLog $model)
- 请求服务
    - 获取日志类型列表 ： types()
    - 操作日志列表查询 ： search(array $params = []): array
    - 获取日志详情 ： view($id)

## 使用示例

```php

class LogController extends Controller
{
    /**
     * @var OperateLog
     */
    public $model;
    /**
     * @var OperateLogService
     */
    public $service;

    /**
     * LogController constructor.
     * {@inheritdoc}
     */
    public function __construct($id, $module, $config = [], OperateLogService $service, OperateLog $model)
    {
        parent::__construct($id, $module, $config);
        $this->model   = $model;
        $this->service = $service->setModel($model);
    }

    /**
     * 操作日志列表查询
     *
     * @throws InvalidConfigException
     * @throws ParameterException
     */
    public function actionSearch()
    {
        // 参数校验
        $this->validateParams([
            ['id', 'integer', 'min' => 1],
            ['trace_id', 'string', 'length' => 32],
            ['type', 'in', 'range' => array_keys($this->model::types())],
            [['keyword', 'username', 'message'], 'string'],
            ['keyword', 'string'],
            ['uid', 'integer', 'min' => 1],
        ], [
            'id'       => '日志ID',
            'trace_id' => '链路ID',
            'type'     => '日志类型',
            'keyword'  => '关键字',
            'message'  => '消息',
            'uid'      => '用户ID',
            'username' => '用户名',
        ]);
        // 参数获取
        $params = array_merge($this->pageParams(), [
            'id'       => $this->getParam('id', null),
            'trace_id' => $this->getParam('trace_id', null),
            'type'     => $this->getParam('type', null),
            'keyword'  => $this->getParam('keyword', null),
            'message'  => $this->getParam('message', null),
            'uid'      => $this->getParam('uid', null),
            'username' => $this->getParam('username', null),
        ]);
        // 获取数据并返回
        return $this->service->search($params);
    }

    /**
     * 查询日志详情
     *
     * @return OperateLog|null
     * @throws ParameterException
     * @throws InvalidConfigException
     */
    public function actionView()
    {
        // 参数校验
        $this->validateParams([
            ['id', 'required'],
            ['id', 'integer', 'min' => 1],
        ], [
            'id' => '日志ID',
        ]);
        // 参数获取
        $id = $this->getParam('id');
        // 获取数据并返回
        return $this->service->view($id);
    }
}
```
