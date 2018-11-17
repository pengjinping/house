<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\product\BuildHouse;

?>
<script type="text/javascript">
    function uploadPicBase(fileobj, formid, cb){
        var animateimg = $(fileobj).val() || '';
        var animateimgs = animateimg.split('.');    // 得到的图片地址拆分
        var imgext = animateimgs[animateimgs.length - 1];

        if(  "|png|PNG|gif|GIF|jpg|JPG|jpeg|JPEG".indexOf(imgext) <= 0 ){
            alert('文件类型错误,请上传图片类型');
        }

        var fileSize = $(fileobj).get(0).files[0].size;           //获取上传的文件大小
        if(fileSize > 3 * 1024000 ){
            alert('上传的文件不能超过3MB');
        }

        var formImg = new FormData( document.getElementById(formid) );
        $.ajax({type: "post", data: formImg, dataType : 'json',
            url: "http://static.seohouse.top/upload.php?project=house",
            contentType: false,  // 注意这里应设为false
            processData: false,  //false
            cache: false,        //缓存
            success: function(data){
                typeof cb == 'function' && cb(data);
            }
        });
    }
    // 上传图片
    function uploadPic(fileobj, formid){
        uploadPicBase(fileobj, formid, function (data) {
            if(data && data.code == 200){
                $("#buildhouse-image").val(data.data);
            }else{
                alert(data.msg);
            }
        });
    }
    // 上传图片1
    function uploadPic1(fileobj, formid){
        uploadPicBase(fileobj, formid, function (data) {
            if(data && data.code == 200){
                var images = $("#buildhouse-images").html();
                images = images ? images + "\n" : '';
                $("#buildhouse-images").html(images + data.data);
            }else{
                alert(data.msg);
            }
        });
    }
</script>

<div class="build-house-form">

    <form id= "uploadForm" enctype ="multipart/form-data" style="display:none" >
        <input type="file" name="file" id="fileupload" onchange="uploadPic(this, 'uploadForm');" />
    </form>

    <form id= "uploadForm1" enctype ="multipart/form-data" style="display:none" >
        <input type="file" name="file" id="fileupload1" onchange="uploadPic1(this, 'uploadForm1');" />
    </form>

    <?php $form = ActiveForm::begin([
    	'options' => ['class' => 'form-horizontal'],
    	'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
    	]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'area_id')->dropDownList(\common\models\Area::getCacheArea(3)) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'village')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'flag')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'leader')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lead_phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-buildhouse-image has-success">
        <label class="col-sm-2 control-label" for="buildhouse-image">缩略图</label>
        <div class="col-sm-8">
            <input type="text" id="buildhouse-image" class="form-control" name="BuildHouse[image]" value="<?=$model->image?>" maxlength="100" aria-invalid="false">
            <span class="help-block m-b-none"></span>
            <input type="button" value="编辑缩略图" onclick="fileupload.click()" /><br>
        </div>
    </div>

    <div class="form-group field-buildhouse-images has-success">
        <label class="col-sm-2 control-label" for="buildhouse-images">简介图</label>
        <div class="col-sm-8">
            <textarea id="buildhouse-images" class="form-control" name="BuildHouse[images]" rows="3" aria-invalid="false"><?= $model->images; ?></textarea>
            <span class="help-block m-b-none"></span>
            <input type="button" value="添加简介图" onclick="fileupload1.click()" /> [最多添加5张]<br>
        </div>
    </div>

    <?= $form->field($model, 'type')->dropDownList(BuildHouse::$TYPE_MAP) ?>
    <?= $form->field($model, 'status')->dropDownList(BuildHouse::$STATUS_MAP) ?>
    <?= $form->field($model, 'top')->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelContent, 'content')->widget('kucha\ueditor\UEditor')->label('内容') ?>

    <div class="form-group">
    	<div class="col-sm-4 col-sm-offset-2">
        	<?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    	</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
