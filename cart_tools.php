<?php
function is_item_in_cart(array $item, array $cart)
{
    return array_key_exists($item['item_id'], $cart);
}

function add_to_cart(array $item, array &$cart)
{
    $item_id = $item['item_id'];
    if (is_item_in_cart($item, $cart)) {
        $cart[$item_id]['quantity']++;
    } else {
        $cart[$item_id] = array('quantity' => 1, 'price' => $item['price']);
    }
}
