<?php

$phoneCollection = collect([
    new \App\Models\Phone('12', '03456789'),
    new \App\Models\Phone('34', '05678912'),
]);

$superModel = new \App\Models\SuperModel();
$superModel->phones = new \Grummfy\EloquentExtendedCast\ValueObject\ReadOnlyCollection($phoneCollection);
$superModel->descriptions = new \Grummfy\EloquentExtendedCast\ValueObject\ReadOnlyCollection();
$superModel->addPhone('56', '078901234');
$superModel->descriptions->push(new \App\Models\TranslatableLabel('en', 'Something to say'));
$superModel->descriptions->push(new \App\Models\TranslatableLabel('fr', 'Quelque chose à dire'));
$superModel->save();

foreach ($superModel->phones as $phone)
{
	echo $phone, PHP_EOL;
}

echo $superModel->getDescription('fr'), PHP_EOL;
