<?php if(get_field('endereco', 38)):
    $endereco = get_field('endereco', 38);
    $address = explode( "," , $endereco['address']);
?>
      

<div class="map">
    <div class="marker" data-lat="<?php echo $endereco['lat']; ?>" data-lng="<?php echo $endereco['lng']; ?>"></div>
</div>

<?php endif; ?>