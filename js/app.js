const upload_file = document.getElementById('upload-file');
const gallery_grid = document.getElementById('gallery-grid');

//get all the images for upload folder
window.addEventListener('load',function (){
    ajax_get('api/get.php');
});

//when submitting the form
upload_file.addEventListener('submit',function (e){
    e.preventDefault();
    let files = this.querySelector("input[name='my_file[]']").files;
    if(files !== undefined && files.length !== 0){
        ajax_post("api/post.php",files);
    }
});

//get existant images in the server
function  ajax_get(url){
    const xhr = new XMLHttpRequest();
    xhr.addEventListener('load',function (){
        if(xhr.status === 200){
            let images = JSON.parse(xhr.responseText);
            if(images.count !== undefined && images.count == 0){
                gallery_grid.style.display = "block";
                gallery_grid.innerHTML = "<h2 class='text-center'>Gallery Is Empty</h2>"
            }else{
                gallery_grid.style.display = "grid";
                for(let img in images){
                    gallery_grid.innerHTML += '<img src="upload'+images[img]+'" alt="image gallery" />' ;
                }
            }
        }
    });
    xhr.open('GET',url,true);
    xhr.send();
}

//the ajax request to post images
function ajax_post(url,files){

    const xhr = new XMLHttpRequest();
    const fd = new FormData();

    xhr.addEventListener('load',function (){
        if(xhr.status === 200){
            let response = JSON.parse(xhr.responseText);
            let on_success = response.success;
            let images_path = response.path;
            let on_success_len = on_success.length;
            for (let i = 0; i < on_success_len; i++){
                if(on_success[i]){
                    if(gallery_grid.style.display == "block"){
                        gallery_grid.style.display = "grid";
                        gallery_grid.innerHTML = "";
                    }
                    gallery_grid.innerHTML += '<img src="'+images_path[i].slice(1)+'" alt="image gallery" />';
                }
            }
        }
    });
    xhr.open('POST',url,true);
    for(let file in files ){
        if(files.hasOwnProperty(file))
            fd.append('my_file[]', files[file]);
    }
    xhr.send(fd);
}