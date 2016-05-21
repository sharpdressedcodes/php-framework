<svg id="<?php echo $id; ?>" class="<?php echo $class; ?>" width="120px" height="120px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="display: none;">
	<rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
	<circle cx="50" cy="50" r="40" stroke="<?php echo $backgroundColour; ?>" fill="none" stroke-width="10" stroke-linecap="round"></circle>
	<circle cx="50" cy="50" r="40" stroke="<?php echo $foreColour; ?>" fill="none" stroke-width="6" stroke-linecap="round">
		<animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"></animate>
		<animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate>
	</circle>
</svg>