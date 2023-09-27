<!DOCTYPE html>
<html>
<head>
	<title>User list - PDF</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
	<a href="{{ route('admin.generate-pdf',['download'=>'pdf']) }}">Download PDF</a>
    
	

    

    <table class="table table-bordered">
		<thead>
			<tr><th>Name</th>
			<th>Email</th>
		</tr></thead>
		<tbody>
						<tr>
				<td>Super</td>
				<td>admin@admin.com</td>
			</tr>
						<tr>
				<td>Vendor 1</td>
				<td>vendor1@gmail.com</td>
			</tr>
						<tr>
				<td>Vendor 2</td>
				<td>vendor2@gmail.com</td>
			</tr>
					</tbody>
	</table>

</div>
</body>
</html>