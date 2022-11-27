<table>
  <thead>
    <tr>
      @forelse ($headers as $h)
        @if($loop->first)
          <th>No</th>
        @elseif(!in_array($h, $exc))
          <th>{{ $h }}</th>
        @endif
      @empty        
      @endforelse
    </tr>
  </thead>
  <tbody>
    @forelse ($data as $keys => $d)
      <tr>
        @forelse ($headers as $head)
          @if($loop->first)
            <td>{{ $loop->parent->iteration }}</td>
          @elseif(!in_array($head, $exc))
            <td>{{ $d->$head }}</td>
          @endif
        @empty          
        @endforelse        
      </tr>      
    @empty      
    @endforelse
  </tbody>
</table>