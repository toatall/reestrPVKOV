<?php

    use yii\bootstrap\Html;

    foreach ($list as $l)
    {
        echo Html::a($l, 'js:return false;', ['onclick'=>'js:setValue(\'' . $l . '\');', 'data-dismiss'=>'modal']) . '<br />';          
    }
    
?>

<script type="text/javascript">
	function setValue(v)
	{
		
		// если есть текст, то запятую
		//   если есть запятая, то не надо ничего
		old = $('<?= $inputId ?>').val();
		old = old.trim();
		if (old.length > 1)
		{
			if (old.substring(old.length-1) != ',' && old.substring(old.length-1) != ';')
			{
				old = old + ', ';
			}
		}	
		
		$('<?= $inputId ?>').val(old + v);
		return false;
	}
</script>    