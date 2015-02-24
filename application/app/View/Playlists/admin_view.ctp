<?php /* @var $this View */ ?>
<?php echo $this->element( 'admin/panels/record_panel', array( 'id' => $playlist['Playlist']['id'] ) ); ?>
<div class="row-fluid">
  <div class="span12">
    <h2><em>Playlist: <?php echo $playlist['Playlist']['id'] ?></em></h2>
    <dl class="dl-horizontal well">
      <dt>id</dt>
      <dd><?php echo h($playlist['Playlist']['id']); ?>&nbsp;</dd>
      <dt>UUID</dt>
      <dd><?php echo h($playlist['Playlist']['playlist_uuid']); ?>&nbsp;</dd>
      <dt>Songids</dt>
      <dd><?php echo h($playlist['Playlist']['songids']); ?>&nbsp;</dd>
      <dt>Creado</dt>
      <dd><?php echo h($playlist['Playlist']['created']); ?>&nbsp;</dd>
    </dl>
  </div>
</div>