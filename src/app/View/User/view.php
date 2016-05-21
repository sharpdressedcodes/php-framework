<div class="container user-container">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>
					Users
					<span class="badge user-badge"><?php echo $total; ?></span>
				</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<section id="user-errors-container"<?php if (count($errorMessages) > 0): ?> style="display: block;"<?php endif; ?>>

				<button type="button" class="close" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

				<?php if (count($errorMessages) > 0): ?>
					<?php foreach ($errorMessages as $message): ?>
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
					'view' => $ajaxSearchFormView,
					'action' => $searchAction,
					'query' => $query,
					'queryMax' => $queryMax,
					'closeSearchUrl' => $closeSearchUrl,
					'closeSearchText' => $closeSearchText,
					'formId' => $searchFormId,
					'closeId' => $closeSearchId,
					'csrf' => $csrf,
					'placeHolder' => $searchPlaceHolder,
					'csrfName' => $csrfName,
					'queryName' => $queryName,
					'queryRegEx' => $queryRegEx,
					'queryRegExMods' => $queryRegExMods,
				))); ?>
			</div>

		</div>
		<div class="col-md-4"></div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<?php $this->renderWidget(new \WebsiteConnect\Framework\Widget\AjaxLoader\Widget(array(
				'controller' => $this,
				'view' => $ajaxLoaderView,
				'backgroundColour' => '#bbbbbb',
				'foreColour' => '#337ab7',
				'id' => $ajaxLoaderId,
			))); ?>
		</div>
		<div class="col-md-4"></div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<section class="user-section">
				<?php $this->renderWidget(new \WebsiteConnect\Framework\Widget\AjaxTable\Widget(array(
					'controller' => $this,
					'view' => $ajaxTableView,
					'data' => $data,
					'total' => $total,
					'start' => $start,
					'limit' => $limit,
					'allowedFields' => $allowedFields,
					'sortUrlCallback' => $sortUrlCallback,
					'paginationUrlCallback' => $paginationUrlCallback,
					'startPage' => $startPage,
					'endPage' => $endPage,
					'json' => $json,
					'tableId' => $tableId,
					'paginationId' => $paginationId,
					'current' => $current,
					'pages' => $pages,
					'maxPages' => $maxPages,
				))); ?>
			</section>
		</div>
		<div class="col-md-4"></div>
	</div>

</div>

<?php $this->renderWidget(new \WebsiteConnect\Framework\Widget\BootstrapDialog\Widget(array(
	'controller' => $this,
	'view' => $dialogView,
	'id' => $dialogId,
	'title' => $dialogTitle,
))); ?>

<script>

	(function(){

		'use strict';

		var ajaxTable = null;
		var ajaxSearchForm = null;
		var ajaxLoader = null;
		var $modal = null;
		var $errorsContainer = null;

		window.addEventListener('load', onLoad, false);

		function onLoad(){

			$modal = $('#<?php echo $dialogId; ?>');
			$errorsContainer = $('#user-errors-container');

			$errorsContainer.find('.close').on('click', function(event){
				$errorsContainer.slideUp();
			});

			$('.user-options').each(function(index, element){
				$(element).on('click', showUserInformation);
			});

			loadAjaxLoader();
			loadAjaxSearchForm();
			loadAjaxTable();

		}

		function loadAjaxLoader(){

			ajaxLoader = new WebsiteConnect.widgets.AjaxLoader({
				id: '<?php echo $ajaxLoaderId; ?>',
				condition: function(settings){
					return settings.url.indexOf('action=user') > -1;
				}
			});

			ajaxLoader.on('show.before', function(data){
				ajaxLoader.condition(data.settings) && $errorsContainer.slideUp();
			});

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

			var $table = $(ajaxTable.table);
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

			total === 0 && onError('<?php echo $noUsersMessage; ?>');

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