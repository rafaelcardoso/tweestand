<div id="messages-list">
	@if(isset($response_data))
		@if(array_key_exists('messages', $response_data))
			@foreach ($response_data['messages'] as $type => $field)
				@foreach($field as $msg)
					@foreach($msg as $text)
						<div class="alert alert-{{ $type }}">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<i class="icon-info-sign"></i>
							<span>{{ $text }}</span>
						</div>
					@endforeach
				@endforeach
			@endforeach
		@endif
	@endif
</div>