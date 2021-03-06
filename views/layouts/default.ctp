<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('ELEFE :: Sistema de Ubicaciones ::'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('estanterias');

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<table class="header" cellpadding="0" cellspacing="0">
				<tr>
					<td class="logo">
						<h1 class="default">
						<?php echo $this -> Html -> image('logo_120.png', 
										array('alt'=> __('ELEFE', true), 'class' => 'default_logo')); ?>
						Sistema de Ubicaciones
						</h1>
					</td>
					<td class="header">
						<div class="header">
							<?php echo $this -> Html -> link('Pedidos', 
										array('controller' => 'pedidos', 'action' => 'index')); ?>
						</div>
					</td>
					<td class="header">
						<div class="header">
							<?php echo $this -> Html -> link('Ubicados', 
										array('controller' => 'ubicados', 'action' => 'index')); ?>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>