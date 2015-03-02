<?php

namespace dee\angular;

use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;

/**
 * DataSource
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DataSource extends \yii\base\Object
{
    /**
     * @var QueryInterface 
     */
    public $query;

    /**
     * @var array 
     */
    public $queryParams = [];

    /**
     * @var string 
     */
    public $filterParam = 'q';

    /**
     * @var boolean 
     */
    public $pagination = true;

    /**
     * @var string 
     */
    public $pageParam = 'page';

    /**
     * @var string 
     */
    public $pageSizeParam = 'per-page';

    /**
     * @var string 
     */
    public $sortParam = 'sort';

    /**
     * @var boolean whether the sorting can be applied to multiple attributes simultaneously.
     * Defaults to false, which means each time the data can only be sorted by one attribute.
     */
    public $enableMultiSort = false;

    /**
     * @var array list of attributes that are allowed to be sorted. Its syntax can be
     * described using the following example:
     *
     * ```php
     * [
     *     'age',
     *     'name' => [
     *         'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
     *         'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
     *         'default' => SORT_DESC,
     *         'label' => 'Name',
     *     ],
     * ]
     * ```
     *
     * In the above, two attributes are declared: "age" and "name". The "age" attribute is
     * a simple attribute which is equivalent to the following:
     *
     * ```php
     * 'age' => [
     *     'asc' => ['age' => SORT_ASC],
     *     'desc' => ['age' => SORT_DESC],
     *     'default' => SORT_ASC,
     *     'label' => Inflector::camel2words('age'),
     * ]
     * ```
     *
     * The "name" attribute is a composite attribute:
     *
     * - The "name" key represents the attribute name which will appear in the URLs leading
     *   to sort actions.
     * - The "asc" and "desc" elements specify how to sort by the attribute in ascending
     *   and descending orders, respectively. Their values represent the actual columns and
     *   the directions by which the data should be sorted by.
     * - The "default" element specifies by which direction the attribute should be sorted
     *   if it is not currently sorted (the default value is ascending order).
     * - The "label" element specifies what label should be used when calling [[link()]] to create
     *   a sort link. If not set, [[Inflector::camel2words()]] will be called to get a label.
     *   Note that it will not be HTML-encoded.
     *
     * Note that if the Sort object is already created, you can only use the full format
     * to configure every attribute. Each attribute must include these elements: `asc` and `desc`.
     */
    public $sortAttributes = [];

    /**
     * @var array the order that should be used when the current request does not specify any order.
     * The array keys are attribute names and the array values are the corresponding sort directions. For example,
     *
     * ```php
     * [
     *     'name' => SORT_ASC,
     *     'created_at' => SORT_DESC,
     * ]
     * ```
     * 
     */
    public $defaultOrder;

    /**
     * @var string the character used to separate different attributes that need to be sorted by.
     */
    public $sortSeparator = ',';

    /**
     * @var \Closure 
     * function ($query, $params, $object){
     * 
     * }
     */
    public $searchCallback;

    /**
     * @var array 
     */
    public $fieldMap = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
    }

    /**
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $params = ArrayHelper::merge($this->queryParams, $params);

        // pagination
        if ($this->pagination) {
            $pagination = [
                'pageParam' => $this->pageParam,
                'pageSizeParam' => $this->pageSizeParam,
                'params' => $params,
            ];
        } else {
            $pagination = false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => $pagination,
            'sort' => [
                'enableMultiSort' => $this->enableMultiSort,
                'attributes' => $this->sortAttributes,
                'sortParam' => $this->sortParam,
                'defaultOrder' => $this->defaultOrder,
                'separator' => $this->sortSeparator
            ]
        ]);
        if ($this->searchCallback === null) {
            $this->defaultSearch($this->query, $params);
        } else {
            call_user_func($this->searchCallback, $this->query, $params, $this);
        }
        return $dataProvider;
    }

    /**
     * @param QueryInterface $query
     * @param array $params
     */
    public function defaultSearch($query, $params = [])
    {
        $filters = ArrayHelper::getValue($params, $this->filterParam, []);
        foreach ($filters as $filter) {
            $this->defaultFilter($query, $filter);
        }
    }

    /**
     * @param QueryInterface $query
     * @param array $filter
     */
    public function defaultFilter($query, $filter)
    {
        $field = $filter['field'];
        if (isset($this->fieldMap[$field])) {
            $field = $this->fieldMap[$field];
        }
        $op = isset($filter['op']) ? $filter['op'] : 'contains';
        $value = isset($filter['value']) ? $filter['value'] : '';
        if ($value === '' && $op != 'equal') {
            return;
        }
        switch ($op) {
            case 'contains':
                $query->andFilterWhere([$field => $value]);
                break;
            case 'equal':
                $query->andWhere([$field => $value]);
                break;
            case 'notequal':
                $query->andWhere(['<>', $field, $value]);
                break;
            case 'beginwith':
                $query->andWhere(['like', $field, $value . '%']);
                break;
            case 'endwith':
                $query->andWhere(['like', $field, '%' . $value]);
                break;
            case 'less':
                $query->andWhere(['<', $field, $value]);
                break;
            case 'lessorequal':
                $query->andWhere(['<=', $field, $value]);
                break;
            case 'greater':
                $query->andWhere(['>', $field, $value]);
                break;
            case 'greaterorequal':
                $query->andWhere(['>=', $field, $value]);
                break;
        }
    }
}