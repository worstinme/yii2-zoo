<?php echo "<?php"; ?>

namespace app\controllers;

use Yii;

<?php if (!empty($modelName)): ?>
use app\models\<?=$modelName?> as Items;
<?php else: ?>
use worstinme\zoo\models\Items;
<?php endif ?>
use worstinme\zoo\models\Categories;
use worstinme\zoo\models\S as s;

use yii\web\NotFoundHttpException;

class <?= $controllerName ?> extends \worstinme\zoo\Controller
{

 
}
