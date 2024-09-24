<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Add-Post</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <form action="" id="addform">
                    <h5>Add post</h5>
                    <div class="mb-3">
                        {{-- <label for="exampleFormControlInput1" class="form-label">Form Title</label> --}}
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        {{-- <label for="exampleFormControlTextarea1" class="form-label">Form Discription</label> --}}
                        <textarea class="form-control" id="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        {{-- // <label for="exampleFormControlInput1" class="form-label"></label> --}}
                        <input type="file" class="form-control" id="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var addform = document.querySelector("#addform");
        addform.onsubmit = async (e) => {
            e.preventDefault();

            const token = localStorage.getItem('api_token');

            const title = document.querySelector("#title").value;
            const description = document.querySelector("#description").value;
            const image = document.querySelector("#image").files[0];

            var formdata = new FormData();
            formdata.append('title', title);
            formdata.append('description', description);
            formdata.append('image', image);

            let response = await fetch('/api/posts', {
                    method: 'POST',
                    body: formdata,
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.href = "http://localhost:8000/allpost";
                });
        }
    </script>
</body>

</html>
