<?php require_once('scripts.php'); ?>

<div id="app">
	<div id="app-navigation">
		<?php print_unescaped($this->inc('navigation/index')); ?>
		<?php print_unescaped($this->inc('settings/index')); ?>
	</div>

	<div id="app-content">
		<div id="app-content-wrapper">
			<?php print_unescaped($this->inc('content/changelog')); ?>
			<?php print_unescaped($this->inc('content/devis')); ?>
		</div>
	</div>
</div>

