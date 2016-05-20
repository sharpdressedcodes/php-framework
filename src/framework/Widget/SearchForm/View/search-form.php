<section class="search-section">
	<form id="<?php echo $formId; ?>" action="<?php echo $action; ?>" method="post" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="<?php echo $csrfName; ?>" value="<?php echo $csrf; ?>">
		<div class="input-group">
			<input name="<?php echo $queryName; ?>" type="search" class="form-control" placeholder="<?php echo $placeHolder; ?>" value="<?php echo $query; ?>" required="required" maxlength="<?php echo $queryMax; ?>">
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit" title="Search">
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</span>
		</div>
	</form>

</section>

<section id="<?php echo $closeId; ?>"<?php if ($query !== ''): ?> style="display: block;"<?php endif; ?>>
	<a class="close-search" href="?<?php echo $closeSearchUrl; ?>"><?php echo $closeSearchText ?></a>
</section>
