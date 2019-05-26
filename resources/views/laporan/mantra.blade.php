<html>
  <body>
    <form action="{{route('saveToMantra')}}" method="post">
      {{csrf_field()}}
      <input name="nip" type="text" value="198011042006041009">
      <input name="periode" type="text" value="04-2019">
      <button type="submit" name="button">submit</button>
    </form>
  </body>
</html>
