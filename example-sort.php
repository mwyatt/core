uasort($data, function ($a, $b) use ($property, $type, $order) {
    if ($type == 'float') {
        $a->$property += 0;
        $b->$property += 0;
    }
    if ($type == 'string') {
        if ($order == 'asc') {
            return strcasecmp($a->$property, $b->$property);
        } else {
            return strcasecmp($b->$property, $a->$property);
        }
    } elseif ($type == 'integer' || $type == 'float') {
        if ($order == 'asc') {
            if ($a->$property == $b->$property) {
                return 0;
            }
            return $a->$property < $b->$property ? -1 : 1;
        } else {
            if ($a->$property == $b->$property) {
                return 0;
            }
            return $a->$property > $b->$property ? -1 : 1;
        }
    }
});
