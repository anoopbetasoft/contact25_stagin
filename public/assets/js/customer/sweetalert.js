<script src="sweetalert2.all.min.js"></script>
<script>
function barcode_scan()
{
	var data = "Download the app and you'll be able to quickly list your stuff faster using a barcode scanner.Links to app stores<span style=float:right;cursor: pointer;><img src=http://newapp.contact25.com/assets/images/store_app.png><img src=http://newapp.contact25.com/assets/images/store_play.png></span>";
	//swal('Barcode Scanner',data,'success');
	//swal({ title: '<i>HTML</i> <u>example</u>', type: 'info', html: true, text: 'You can use <b>bold text</b>, ' + '<a href="//github.com">links</a> ' + 'and other HTML tags', showCloseButton: true, showCancelButton: true, confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!', cancelButtonText: '<i class="fa fa-thumbs-down"></i>' })
	/*Swal.fire({
    title: 'Barcode Scanner',
    text: data,
    imageUrl: 'https://wasabiBD.github.io/test-repo/dia2/images/feito.png',
    imageWidth: 164,
    imageHeight: 205,
    padding: 10,
    animation: true,
  });*/
  Swal.fire(
  'Good job!',
  'You clicked the button!',
  'success'
)
}
</script>