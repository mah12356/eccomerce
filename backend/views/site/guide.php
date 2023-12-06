<?php
/** @var $subject backend\controllers\SiteController */
?>

<div class="container">
    <div class="row">
        <h1><?= $this->title ?></h1>
        <?php
        switch ($subject) {
            case 'package':
        ?>
            <video class="mt-3 w-100 h-auto" controls>
                <source src="/upload/statics/package.mp4" type="video/mp4">
            </video>
        <?php
                break;
          	case 'regimes':
       	?>
      		<video class="mt-3 w-100 h-auto" controls>
                <source src="/upload/statics/regimes.mp4" type="video/mp4">
            </video>
      	<?php
            	break;
            default:
        ?>
        <?php
                break;
        }
        ?>
    </div>
</div>
