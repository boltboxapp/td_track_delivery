<p>
    <label for="<?=$__widget->get_field_id('title')?>">Заголовок</label>
    <input type="text" name="<?=$__widget->get_field_name('title')?>" id="<?=$__widget->get_field_id('title')?>"
           value="<?php if( isset($title) ) echo esc_attr( $title ); ?>" class="widefat">
</p>
<p>
    <label for="<?=$__widget->get_field_id('newpost_apikey')?>">Новая Почта API-key</label>
    <input type="text" name="<?=$__widget->get_field_name('newpost_apikey')?>" id="<?=$__widget->get_field_id('newpost_apikey')?>"
        value="<?php if( isset($newpost_apikey) ) echo esc_attr( $newpost_apikey ); ?>" class="widefat">
</p>
<p>
    <label for="<?=$__widget->get_field_id('ukrpost_guid')?>">Укрпочта GUID</label>
    <input type="text" name="<?=$__widget->get_field_name('ukrpost_guid')?>" id="<?=$__widget->get_field_id('ukrpost_guid')?>"
        value="<?php if( isset($ukrpost_guid) ) echo esc_attr( $ukrpost_guid ); ?>" class="widefat">
</p>
    
