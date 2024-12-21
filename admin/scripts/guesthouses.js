let add_guesthouse_form = document.getElementById('add_guesthouse_form');
    
add_guesthouse_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_guesthouse();
});

function add_guesthouse()
{
  let data = new FormData();
  data.append('add_guesthouse','');
  data.append('name',add_guesthouse_form.elements['name'].value);
  data.append('area',add_guesthouse_form.elements['area'].value);
  data.append('price',add_guesthouse_form.elements['price'].value);
  data.append('quantity',add_guesthouse_form.elements['quantity'].value);
  data.append('adult',add_guesthouse_form.elements['adult'].value);
  data.append('children',add_guesthouse_form.elements['children'].value);
  data.append('desc',add_guesthouse_form.elements['desc'].value);

  let features = [];
  add_guesthouse_form.elements['features'].forEach(el =>{
    if(el.checked){
      features.push(el.value);
    }
  });

  let facilities = [];
  add_guesthouse_form.elements['facilities'].forEach(el =>{
    if(el.checked){
      facilities.push(el.value);
    }
  });

  data.append('features',JSON.stringify(features));
  data.append('facilities',JSON.stringify(facilities));

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('add-guesthouse');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','New guesthouse added!');
      add_guesthouse_form.reset();
      get_all_guesthouses();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

function get_all_guesthouses()
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('guesthouse-data').innerHTML = this.responseText;
  }

  xhr.send('get_all_guesthouses');
}

let edit_guesthouse_form = document.getElementById('edit_guesthouse_form');

function edit_details(id)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    let data = JSON.parse(this.responseText);

    edit_guesthouse_form.elements['name'].value = data.guesthousedata.name;
    edit_guesthouse_form.elements['area'].value = data.guesthousedata.area;
    edit_guesthouse_form.elements['price'].value = data.guesthousedata.price;
    edit_guesthouse_form.elements['quantity'].value = data.guesthousedata.quantity;
    edit_guesthouse_form.elements['adult'].value = data.guesthousedata.adult;
    edit_guesthouse_form.elements['children'].value = data.guesthousedata.children;
    edit_guesthouse_form.elements['desc'].value = data.guesthousedata.description;
    edit_guesthouse_form.elements['guesthouse_id'].value = data.guesthousedata.id;

    edit_guesthouse_form.elements['features'].forEach(el =>{
      if(data.features.includes(Number(el.value))){
        el.checked = true;
      }
    });

    edit_guesthouse_form.elements['facilities'].forEach(el =>{
      if(data.facilities.includes(Number(el.value))){
        el.checked = true;
      }
    });
  }

  xhr.send('get_guesthouse='+id);
}

edit_guesthouse_form.addEventListener('submit',function(e){
  e.preventDefault();
  submit_edit_guesthouse();
});

function submit_edit_guesthouse()
{
  let data = new FormData();
  data.append('edit_guesthouse','');
  data.append('guesthouse_id',edit_guesthouse_form.elements['guesthouse_id'].value);
  data.append('name',edit_guesthouse_form.elements['name'].value);
  data.append('area',edit_guesthouse_form.elements['area'].value);
  data.append('price',edit_guesthouse_form.elements['price'].value);
  data.append('quantity',edit_guesthouse_form.elements['quantity'].value);
  data.append('adult',edit_guesthouse_form.elements['adult'].value);
  data.append('children',edit_guesthouse_form.elements['children'].value);
  data.append('desc',edit_guesthouse_form.elements['desc'].value);

  let features = [];
  edit_guesthouse_form.elements['features'].forEach(el =>{
    if(el.checked){
      features.push(el.value);
    }
  });

  let facilities = [];
  edit_guesthouse_form.elements['facilities'].forEach(el =>{
    if(el.checked){
      facilities.push(el.value);
    }
  });

  data.append('features',JSON.stringify(features));
  data.append('facilities',JSON.stringify(facilities));

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('edit-guesthouse');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','Guesthouse data edited!');
      edit_guesthouse_form.reset();
      get_all_guesthouses();
    }
    else{
      alert('error','Server Down!');
    }
  }

  xhr.send(data);
}

function toggle_status(id,val)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText==1){
      alert('success','Status toggled!');
      get_all_guesthouses();
    }
    else{
      alert('success','Server Down!');
    }
  }

  xhr.send('toggle_status='+id+'&value='+val);
}

let add_image_form = document.getElementById('add_image_form');

add_image_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_image();
});

function add_image()
{
  let data = new FormData();
  data.append('image',add_image_form.elements['image'].files[0]);
  data.append('guesthouse_id',add_image_form.elements['guesthouse_id'].value);
  data.append('add_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 'inv_img'){
      alert('error','Only JPG, WEBP or PNG images are allowed!','image-alert');
    }
    else if(this.responseText == 'inv_size'){
      alert('error','Image should be less than 2MB!','image-alert');
    }
    else if(this.responseText == 'upd_failed'){
      alert('error','Image upload failed. Server Down!','image-alert');
    }
    else{
      alert('success','New image added!','image-alert');
      guesthouse_images(add_image_form.elements['guesthouse_id'].value,document.querySelector("#guesthouse-images .modal-title").innerText)
      add_image_form.reset();
    }
  }
  xhr.send(data);
}

function guesthouse_images(id,rname)
{
  document.querySelector("#guesthouse-images .modal-title").innerText = rname;
  add_image_form.elements['guesthouse_id'].value = id;
  add_image_form.elements['image'].value = '';

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('guesthouse-image-data').innerHTML = this.responseText;
  }

  xhr.send('get_guesthouse_images='+id);
}

function rem_image(img_id,guesthouse_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('guesthouse_id',guesthouse_id);
  data.append('rem_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Image Removed!','image-alert');
      guesthouse_images(guesthouse_id,document.querySelector("#guesthouse-images .modal-title").innerText);
    }
    else{
      alert('error','Image removal failed!','image-alert');
    }
  }
  xhr.send(data);  
}

function thumb_image(img_id,guesthouse_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('guesthouse_id',guesthouse_id);
  data.append('thumb_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/guesthouses.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Image Thumbnail Changed!','image-alert');
      guesthouse_images(guesthouse_id,document.querySelector("#guesthouse-images .modal-title").innerText);
    }
    else{
      alert('error','Thumbnail update failed!','image-alert');
    }
  }
  xhr.send(data);  
}

function remove_guesthouse(guesthouse_id)
{
  if(confirm("Are you sure, you want to delete this guesthouse?"))
  {
    let data = new FormData();
    data.append('guesthouse_id',guesthouse_id);
    data.append('remove_guesthouse','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/guesthouses.php",true);

    xhr.onload = function()
    {
      if(this.responseText == 1){
        alert('success','Guesthouse Removed!');
        get_all_guesthouses();
      }
      else{
        alert('error','Guesthouse removal failed!');
      }
    }
    xhr.send(data);
  }

}

window.onload = function(){
  get_all_guesthouses();
}