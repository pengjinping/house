<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /* 定义常量
    const STATUS_ONLINE = 1;
    const STATUS_DELETE = 0;
    public static $STATUS_MAP = [
        self::STATUS_DELETE => '删除',
        self::STATUS_ONLINE => '在线',
    ];
   */
<?php
    foreach ($labels as $name => $label){
        $comment = $tableSchema->columns[$name]->comment;
        $comments = explode(' ', $comment);
        if( count($comments) > 1 ){
            echo "    //" .array_shift($comments) ."\n";
            foreach($comments as $key=>$val){
                echo "    const " . strtoupper($name) . "_{$key} = '{$val}';\n";
            }
            echo '    public static $'. strtoupper($name) .'_MAP = ['."\n";
            foreach($comments as $key=>$val){
                echo "        self::" . strtoupper($name) . "_{$key} => '{$val}',\n ";
            }
            echo "    ];\n\n";
        }
    }
?>

<?php if ($generator->db !== 'db'): ?>
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
    <?php if( $comment = $tableSchema->columns[$name]->comment) : ?>
    <?= "'$name' => '". explode(' ', $comment)[0] ."',\n" ?>
    <?php else: ?>
    <?= "'$name' => " . $generator->generateString(explode(';', $label)[0]) . ",\n" ?>
    <?php endif;?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}
