<?php 
/*
* Template Name: Book Consultation Template
*
*/

$lawyer_id = !empty($_GET['book_id'])  ? $_GET['book_id'] : 0 ;
$lawyer_link = 'https://meetings.hubspot.com/paras-kumar?embed=true';

if($lawyer_id > 0){
	
  	
	$lawyer_book_link = get_field('book_consultation_link',$lawyer_id);
	if($lawyer_book_link){
		$lawyer_link = $lawyer_book_link;
		}
}


?>

<div class="bckto-home">
<a class="btn btn-orange rounded-pill ms-auto"  href="https://propertymates.io/">Go Back</a>
</div>
<!-- Start of Meetings Embed Script -->
    <div class="meetings-iframe-container" data-src="<?php echo $lawyer_link; ?>"></div>
    <script type="text/javascript" src="https://static.hsappstatic.net/MeetingsEmbed/ex/MeetingsEmbedCode.js"></script>
  <!-- End of Meetings Embed Script -->
