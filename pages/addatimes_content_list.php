<?php



$url = 'https://www.addatimes.com/api/video-catelog-list'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$characters = json_decode($data); // decode the JSON feed
 ?>

<style>
table { 
  width: auto; 
  border-collapse: collapse; padding-top:20px
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}
</style>
<table>
	<tbody>
		<tr>
			<th>#</th>
			<th>Title</th>
            <th>Description</th>
            <th>Tv Image</th>
            <th>Type</th>
            <th>Season</th>
            <th>Director</th>
            <th>Primary Language</th>
            <th>Year Of Production</th>
            <th>Duration Per Episode</th>
            <th>Parental Rating</th>
            <th>Episode</th>
            <th>Dubbend in other Language</th>
            <th>video_url</th>   
		</tr>
		<?php foreach ($characters as $character) : ?>
        <tr>
            <td> <?=$i=$i+1;?> </td>
            <td> <?php echo $character->title; ?> </td>
            <td> <?php echo $character->description; ?> </td>
            <td> <img src="<?php echo $character->tv_image; ?>" height="500" width="500"  /> </td>
            <td> <?php echo $character->type; ?> </td>
            <td> <?php echo $character->season; ?> </td>
            <td> <?php echo $character->director; ?> </td>            
             <td> <?php echo $character->primary_language; ?> </td>
              <td> <?php echo $character->year_of_production; ?> </td>
               <td> <?php echo $character->duration_per_episode; ?> </td>
                <td> <?php echo $character->parental_rating; ?> </td>
                 <td> <?php echo $character->episode; ?> </td>
                 <td> <?php echo $character->dubbend_in_other_language; ?> </td>
                 <td> <?php echo $character->video_url; ?> </td>
        </tr>
		<?php endforeach; ?>
	</tbody>
</table>
