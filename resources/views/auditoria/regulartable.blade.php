 <table class="table table-bordered table-striped mb-none">
    <thead>
        <tr>
            <th>Evento</th>
            <th>Módulo</th>
            {{--<th>Información antes de la modificación</th>
            <th>Información despues de la modificación</th>--}}
            <th>usuario</th>
            <th>fecha</th>
            <th></th>
        
        </tr>
    </thead>
    <tbody class="table-t-body modalscript">                    


@foreach($data['registros'] as $data)

    <tr class="gradeX">
        <td>{{$data->event}}</td>
        <td>{{$data->auditable_type}}</td>
        {{--<td>{{implode(',', $data->old_values)}}</td>
        <td>{{implode(',', $data->new_values)}}</td>--}}
        <td>{{$data->username}}</td>
        <td>{{$data->created_at}}</td>
        <td>
            <a href="#" class="btn btn-primary click" data-typeform="modify" data-taction="registerform" data-field-id="{{$data->id}}"><i class="fa fa-eye"></i></a>
        </td>
       
    </tr>

@endforeach


</tbody>

</table>