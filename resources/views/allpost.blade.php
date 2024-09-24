<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>All-post</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <a href="/addpost" class="btn btn-sm btn-primary">Add Post</a>
                <a href="" class="btn btn-sm btn-danger" id="logoutbtn">Logout</a>

                <div id="postcontainer">

                </div>
            </div>
        </div>
    </div>

    <!-- Single Modal -->
    <div class="modal fade" id="singlepost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="singlepostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="singlepostLabel">Single Post</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- update Modal -->
    <div class="modal fade" id="updatepost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="updatepostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="updatepostLabel">Update Post</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateform">
                    <div class="modal-body">

                        <input type="hidden" id="postId"class="form-control" value="">
                        <b>Post Title</b> <input type="text" id="posttitle"class="form-control" value="">
                        <b>Post Description</b> <input type="text" id="postdescription"class="form-control"
                            value="">
                        <img id="showimage" width="120px">
                        <p>Upload Image</p><input type="file" id="postimage" class="form-control">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="save changes" class="btn btn-warning">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        document.querySelector("#logoutbtn").addEventListener('click', function() {
            const token = localStorage.getItem('api_token');

            fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status === 'true') {
                        window.location.href = "http://localhost:8000/";
                    } else {
                        console.error('Logout failed', data);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        function loaddata() {
            const token = localStorage.getItem('api_token');

            fetch('/api/posts', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    //console.log(data.data.posts);
                    var allpost = data.data.posts;
                    const postContainer = document.querySelector('#postcontainer');
                    var tabledata = `<table class="table">
                        
                            <tr>
                                <th scope="col">image</th>
                                <th scope="col">title</th>
                                <th scope="col">description</th>
                                <th scope="col">view</th>
                                <th scope="col">update</th>
                                <th scope="col">delete</th>
                            </tr>`;
                    allpost.forEach(post => {
                        tabledata += ` <tr>
                                <td><img src="/uploads/${post.image}" alt="" width="100px" /></td>
                                <td>
                                    <h6>${post.title}</h6>
                                </td>
                                <td>
                                    <p>${post.description}</p>
                                </td>
                                <td> <a href="" class="btn btn-primary" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#singlepost">View</a></td>
                                <td><a href="" class="btn btn-warning" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#updatepost">Update</a></td>
                                <td><a href="" onclick=deletepost(${post.id}) class="btn btn-danger">Delete</a> </td>
                            </tr>`

                    });

                    tabledata += `</table>`
                    postContainer.innerHTML = tabledata;

                });
        }

        loaddata();

        // singel post
        var singlepost = document.querySelector("#singlepost");
        if (singlepost) {
            singlepost.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const id = button.getAttribute('data-bs-post')

                const token = localStorage.getItem('api_token');

                fetch(`/api/posts/${id}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.post.posts);
                        const post = data.post.posts;

                        const modalbody = document.querySelector("#singlepost .modal-body");
                        modalbody.innerHTML = "";
                        modalbody.innerHTML = `
                        Title:${post.title},
                        <br>
                        Description:${post.description},
                        <br>
                        <img src="http://localhost:8000/uploads/${post.image}" width="100px"/>
                    `;

                    })
            })
        }

        // update  post modal
        var updatepost = document.querySelector("#updatepost");
        if (updatepost) {
            updatepost.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const id = button.getAttribute('data-bs-post')

                const token = localStorage.getItem('api_token');

                fetch(`/api/posts/${id}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        //console.log(data.post.posts);
                        const post = data.post.posts;

                        document.querySelector("#postId").value = post.id;
                        document.querySelector("#posttitle").value = post.title;
                        document.querySelector("#postdescription").value = post.description;
                        document.querySelector("#showimage").src = `/uploads/${post.image}`;
                    })
            })
        }

        //update post
        var updateform = document.querySelector("#updateform");
        updateform.onsubmit = async (e) => {
            e.preventDefault();

            const token = localStorage.getItem('api_token');

            const postid = document.querySelector("#postId").value;
            const title = document.querySelector("#posttitle").value;
            const description = document.querySelector("#postdescription").value;


            var formdata = new FormData();

            formdata.append('id', postid);
            formdata.append('title', title);
            formdata.append('description', description);

            if (!document.querySelector("#postimage").files[0] == "") {
                const image = document.querySelector("#postimage").files[0];
                formdata.append('image', image);
            }


            let response = await fetch(`/api/posts/${postid}`, {
                    method: 'POST',
                    body: formdata,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.href = "http://localhost:8000/allpost";
                });
        }

        //delete post
        async function deletepost(postid) {
            const token = localStorage.getItem('api_token');

            let response = await fetch(`/api/posts/${postid}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    window.location.href = "http://localhost:8000/allpost";
                });

        }
    </script>

</body>

</html>
