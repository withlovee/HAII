
	<?php foreach ($problems as $problem): ?>
		<tr>
			<td>{{ $problem->id }}</td>
			<td>{{ $problem->start_datetime }}</td>
			<td>{{ $problem->end_datetime }}</td>
			<td>{{ $problem->code }}</td>
			<td>
				<a href="" class="model_btn" data-id="{{ $problem->id }}" data-toggle="modal" data-target="#detail">
				{{ $problem->name }} ต.{{ $problem->tambon_name }} อ.{{ $problem->amphoe_name}} จ.{{ $problem->province_name}}
				</a>
			</td>
			<td>{{ getProblemName($problem->problem_type) }}</td>
			<td>{{ $problem->num }}</td>
			<td>{{ getErrorButton($problem->id, 'true', $problem->status) }}</td>
			<td>{{ getErrorButton($problem->id, 'false', $problem->status) }}</td>
			<td>{{ getErrorButton($problem->id, 'undefined', $problem->status) }}</td>
			<td></td>
		</tr>
	<?php endforeach; ?>