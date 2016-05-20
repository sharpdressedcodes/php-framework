<?php if (count($data) > 0): ?>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<?php foreach ($data[0] as $k => $v): ?>
					<th><?php echo $k; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $row): ?>
				<tr>
					<?php foreach ($row as $k => $v): ?>
						<td><?php echo $v; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>