<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>اپلود پاراگراف</title>
    <link rel="stylesheet" href="{{asset('css/sidebar.css')}}">
</head>
<body>
<h1>اپلود پاراگراف</h1>
<form method="post" enctype="multipart/form-data">
    @csrf
    <textarea name="text" placeholder="پاراگراف" id="text" cols="30" rows="10"></textarea>
    <input type="file" name="image">
    <input type="text" placeholder="alt" name="alt">
    <button class="btn btn-success" type="submit">save</button>
</form>
@include('includeHtmls.sidebar')
<script src="https://cdn.tiny.cloud/1/kzpya4dt62k0cgolnyzjc1l03z8la4gczrcgh0irny26u6ok/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#text',
        language:'fa',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
</body>
</html>
