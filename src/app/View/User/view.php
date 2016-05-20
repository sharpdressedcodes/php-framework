<div class="container user-container">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>
					Users
					<span class="badge user-badge"><?php echo $viewParams['total']; ?></span>
				</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<section id="user-errors-container"<?php if (count($viewParams['errorMessages']) > 0): ?> style="display: block;"<?php endif; ?>>

				<button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

				<?php if (count($viewParams['errorMessages']) > 0): ?>
					<?php foreach ($viewParams['errorMessages'] as $message): ?>
						<div class="user-error bg-danger">
							<?php echo $message; ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="user-error bg-danger"></div>
				<?php endif; ?>

			</section>
		</div>
		<div class="col-md-4"></div>
	</div>

	<div class="row">
		<div class="col-md-8">

			<div id="search-container">
				<?php $this->renderWidget(new \WebsiteConnect\Framework\Widget\AjaxSearchForm\Widget(array(
					'controller' => $this,
					'view' => 'ajax-search-form.php',
					'action' => $viewParams['searchAction'],
					'query' => $viewParams['query'],
					'queryMax' => $viewParams['queryMax'],
					'closeSearchUrl' => $viewParams['closeSearchUrl'],
					'closeSearchText' => $viewParams['closeSearchText'],
					'formId' => $viewParams['searchFormId'],
					'closeId' => $viewParams['closeSearchId'],
					'csrf' => $viewParams['csrf'],
					'placeHolder' => $viewParams['searchPlaceHolder'],
					'csrfName' => $viewParams['csrfName'],
					'queryName' => $viewParams['queryName'],
					'queryRegEx' => $viewParams['queryRegEx'],
					'queryRegExMods' => $viewParams['queryRegExMods'],
				))); ?>
			</div>

		</div>
		<div class="col-md-4"></div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<svg class="ajax-loader" width="120px" height="120px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
				<rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
				<circle cx="50" cy="50" r="40" stroke="#bbbbbb" fill="none" stroke-width="10" stroke-linecap="round"></circle>
				<circle cx="50" cy="50" r="40" stroke="#337ab7" fill="none" stroke-width="6" stroke-linecap="round">
					<animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"></animate>
					<animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate>
				</circle>
			</svg>
		</div>
		<div class="col-md-4"></div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<section class="user-section">
				<?php $this->renderWidget(new \WebsiteConnect\Framework\Widget\AjaxTable\Widget(array(
					'controller' => $this,
					'view' => 'ajax-table.php',
					'data' => $viewParams['data'],
					'total' => $viewParams['total'],
					'start' => $viewParams['start'],
					'limit' => $viewParams['limit'],
					'allowedFields' => $viewParams['allowedFields'],
					'sortUrlCallback' => $viewParams['sortUrlCallback'],
					'paginationUrlCallback' => $viewParams['paginationUrlCallback'],
					'startPage' => $viewParams['startPage'],
					'endPage' => $viewParams['endPage'],
					'json' => $viewParams['json'],
					'tableId' => $viewParams['tableId'],
					'paginationId' => $viewParams['paginationId'],
					'current' => $viewParams['current'],
					'pages' => $viewParams['pages'],
					'maxPages' => $viewParams['maxPages'],
				))); ?>
			</section>
		</div>
		<div class="col-md-4"></div>
	</div>

</div>

<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="gridSystemModalLabel">User Information</h4>
			</div>
			<div class="modal-body">
				<div class="row user-data-row">
					<div class="col-md-4"></div>
					<div class="col-md-8"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>

	(function(){

		'use strict';

		var ajaxTable = null;
		var ajaxSearchForm = null;
		var $ajaxLoader = null;
		var $errorsContainer = null;

		window.addEventListener('load', onLoad, false);

		function onLoad(){

			$errorsContainer = $('#user-errors-container');
			$ajaxLoader = $('.user-container .ajax-loader');

			$('.user-options').each(function(index, element){
				$(element).on('click', showUserInformation);
			});

			$errorsContainer.find('.close').on('click', function(event){
				$errorsContainer.slideUp();
			});

			$(document).ajaxSend(function(event, request, settings) {

				if (settings.url.indexOf('action=user') > -1){
					$errorsContainer.slideUp();
					$ajaxLoader.fadeIn();
				}

			});

			$(document).ajaxComplete(function(event, request, settings) {

				if (settings.url.indexOf('action=user') > -1){
					$ajaxLoader.fadeOut();
				}

			});

			loadAjaxSearchForm();
			loadAjaxTable();

		}

		function loadAjaxSearchForm(){

			ajaxSearchForm = new WebsiteConnect.widgets.AjaxSearchForm({
				formId: '<?php echo $searchFormId; ?>',
				closeId: '<?php echo $closeSearchId; ?>',
				queryMax: '<?php echo $maxPages; ?>',
				queryRegEx: '<?php echo $queryRegEx; ?>',
				queryRegExMods: '<?php echo $queryRegExMods; ?>',
				csrfName: '<?php echo $csrfName; ?>'
			});

			ajaxSearchForm.on('data.receive', function(data){
				$errorsContainer.fadeOut();
			});

			ajaxSearchForm.on('data.receive.success', function(data){

				if (typeof data.error !== 'undefined'){
					onError(data.error);
				} else {
					updateUsers(data);
					ajaxTable.update(data);
					querifyTable(data);
					onNavigate($(ajaxSearchForm.form).attr('action') + (data.query !== '' ? '&query=' + data.query : ''));
				}

			});

			ajaxSearchForm.on(['data.receive.error', 'query.validation.error'], onError);

		}

		function loadAjaxTable(){

			ajaxTable = new WebsiteConnect.widgets.AjaxTable({
				tableId: '<?php echo $tableId; ?>',
				paginationId: '<?php echo $paginationId; ?>',
				maxPages: '<?php echo $maxPages; ?>'
			});

			ajaxTable.on('data.receive', function(data){
				$errorsContainer.fadeOut();
			});

			ajaxTable.on('data.receive.success', function(data){

				if (typeof data.error !== 'undefined'){
					onError(data.error);
				} else {
					updateUsers(data);
					ajaxSearchForm.update(data);
					querifyTable(data);
					onNavigate(data.url);
				}

			});

			ajaxTable.on('data.receive.error', onError);

		}

		function showUserInformation(event){

			var json = null;
			var $modal = $('#user-modal');
			var $body = $modal.find('.modal-body');
			var fragment = document.createDocumentFragment();

			try {
				json = JSON.parse(this.parentNode.parentNode.getAttribute('data-json'));
			} catch (err){
				onError(err.message);
				return;
			}

			Object.keys(json).forEach(function(key){

				var $div = $('<div>');
				var $label = $('<div>');
				var $value = $('<div>');

				$div.attr('class', 'row user-modal-row');
				$label.attr('class', 'col-md-4 user-model-field').text(key);
				$value.attr('class', 'col-md-8').text(json[key]);

				$div.append($label);
				$div.append($value);
				fragment.appendChild($div[0]);

			});

			$body.empty();
			$body.append(fragment);

			$modal.modal();

		}

		function updateUsers(data){

			var $table = $('#<?php echo $viewParams['tableId']; ?>');
			var $body = $table.find('tbody');
			var bodyFragment = document.createDocumentFragment();
			var allowedFields = data.allowedFields;
			var json = data.json;
			var total = data.total;
			var users = data.userData;

			total > 0 && users.forEach(function(row, index){

				var $tr = $('<tr>');
				var rowFragment = document.createDocumentFragment();

				Object.keys(row).forEach(function(key){

					if (allowedFields.indexOf(key) > -1){

						var $td = $('<td>');
						var search = '<button';

						if (row[key].substr(0, search.length) === search){
							$td.html(row[key]);
							$td.find('button').on('click', showUserInformation);
						} else {
							$td.text(row[key]);
						}

						rowFragment.appendChild($td[0]);
					}

				});

				$tr.attr('data-json', json[index]);
				$tr.append(rowFragment);

				bodyFragment.appendChild($tr[0]);

			});

			$body.empty();
			$body.append(bodyFragment);

			$('.user-badge').text(total);

			total === 0 && onError('<?php echo $viewParams['noUsersMessage']; ?>');

		}

		function querifyTable(data){

			ajaxTable.$paginationElements.each(each);
			ajaxTable.$sortElements.each(each);

			function each(index, element){

				var $el = $(element);
				var href = WebsiteConnect.url.strip($el.attr('href'), 'query');

				href !== '#' && data.query && data.query !== '' && (href += '&query=' + data.query);

				$el.attr('href', href);

			}

		}

		function onNavigate(url){
			url && history.pushState && history.pushState({}, document.title, WebsiteConnect.url.strip(url, 'reset'));
		}

		function onError(message){

			var $first = $errorsContainer.find('.user-error');
			var $all = $('#' + $errorsContainer[0].id + '.user-error');

			$all.each(function(index, el){
				$(el).remove();
			});
			$first.empty();

			$first.text(message);

			$errorsContainer.append($first);
			$errorsContainer.slideDown();

		}

	})();

</script>