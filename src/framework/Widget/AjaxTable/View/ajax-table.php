<table id="<?php echo $tableId; ?>" class="table table-striped table-bordered table-hover"<?php if (count($data) > 0): ?> style="display: table;"<?php endif ?>>
	<thead>
		<tr>
			<?php //foreach ($data[0] as $k => $v): ?>
			<?php foreach ($allowedFields as $k): ?>
				<?php if (count($allowedFields) === 0 || array_search($k, $allowedFields) !== false): ?>
					<th>
						<?php if ($k !== ' '): ?>
							<div class="table-sort-container"<?php if (count($data) > 1): ?> style="display: block;"<?php endif; ?>>

								<?php $descUrl = $sortUrlCallback($k, 'desc'); if (!is_null($descUrl)): ?>
									<a class="table-sort-url" href="<?php echo $descUrl; ?>" title="Sort by descending order">
										<span class="glyphicon glyphicon-triangle-top table-sort"></span>
									</a>
								<?php else: ?>
									<span class="glyphicon glyphicon-triangle-top table-sort disabled"></span>
								<?php endif; ?>

								<?php $ascUrl = $sortUrlCallback($k, 'asc'); if (!is_null($ascUrl)): ?>
									<a class="table-sort-url" href="<?php echo $ascUrl; ?>" title="Sort by ascending order">
										<span class="glyphicon glyphicon-triangle-bottom table-sort"></span>
									</a>
								<?php else: ?>
									<span class="glyphicon glyphicon-triangle-bottom table-sort disabled"></span>
								<?php endif; ?>

							</div>
							<?php echo $k; ?>
						<?php endif; ?>
					</th>
				<?php endif; ?>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data as $key => $row): ?>
			<tr data-json="<?php echo $json[$key]; ?>">
				<?php foreach ($row as $k => $v): ?>
					<?php if (count($allowedFields) === 0 || array_search($k, $allowedFields) !== false): ?>
						<td><?php echo $v; ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<div id="<?php echo $paginationId; ?>" class="pagination-container"<?php if ($pages > 1): ?> style="display: block;"<?php endif; ?>>
	<nav>
		<ul class="pagination pagination-sm">
			<li<?php echo $current === 1 ? ' class="disabled"' : ''; ?>>
				<a href="<?php echo $paginationUrlCallback($current, $start, $limit, $pages, 0, 'first'); ?>" aria-label="First" title="First">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
			<li<?php echo $current === 1 ? ' class="disabled"' : ''; ?>>
				<a href="<?php echo $paginationUrlCallback($current, $start, $limit, $pages, 0, 'previous'); ?>" aria-label="Previous" title="Previous">
					<span aria-hidden="true">&lsaquo;</span>
				</a>
			</li>
			<?php for ($i = $startPage; $i < $endPage; $i++): ?>
				<li<?php echo $i === $current ? ' class="active"' : ''; ?>>
					<a href="<?php echo $paginationUrlCallback($current, $start, $limit, $pages, $i); ?>" aria-label="Page <?php echo $i; ?>" title="Page <?php echo $i; ?>">
						<?php echo $i; ?>
					</a>
				</li>
			<?php endfor; ?>
			<?php if ($pages === 0): /* used for cloning in JS. */ ?>
				<li style="display: none;">
					<a href="<?php echo $paginationUrlCallback($current, $start, $limit, $pages + 1, 0); ?>" aria-label="Page 0" title="Page 0">0</a>
				</li>
			<?php endif; ?>
			<li<?php echo $current === $pages ? ' class="disabled"' : ''; ?>>
				<a href="<?php echo $paginationUrlCallback($current, $start, $limit, $pages, 0, 'next'); ?>" aria-label="Next" title="Next">
					<span aria-hidden="true">&rsaquo;</span>
				</a>
			</li>
			<li<?php echo $current === $pages ? ' class="disabled"' : ''; ?>>
				<a href="<?php echo $paginationUrlCallback($current, $start, $limit, $pages, 0, 'last'); ?>" aria-label="Last" title="Last">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>
</div>
