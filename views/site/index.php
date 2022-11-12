<?php

use app\models\database\Container;

/** @var Container[] $containers */
$this->title = 'Container is Up-to-date';
$statesColors = [
    0 => "#777777",
    1 => "#ff4d00",
    2 => "#1f943a"
];
$states = [
    0 => "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"" . $statesColors[0] . "\" class=\"w-6 h-6\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z\" />
            </svg>",
    1 => "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"" . $statesColors[1] . "\" class=\"w-6 h-6\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z\" />
            </svg>",
    2 => "<svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"" . $statesColors[2] . "\" class=\"w-6 h-6\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" />
            </svg>"
];
$statesLabels = [
    0 => "Awaiting processing",
    1 => "A new version is available",
    2 => "Image is up-to-date"
];

?>
<div class="mx-auto max-w-7xl">

    <?php foreach ($containers as $container) {
        $state = 0;
        if ($container->repository->remoteDigest !== null) {
            $state = $container->repository->remoteDigest === $container->digest ? 2 : 1;
        }
        ?>
        <div class="m-4 p-2 border border-gray-300 rounded flex flex-row"
             style="color: <?php echo $statesColors[$state] ?>">
            <div class="basis-0/12 px-3">
                <?php echo $states[$state] ?>
            </div>
            <div class="basis-3/12">
                <?php echo $container->name ?>
            </div>
            <div class="basis-3/12">
                <?php echo $container->repository->name ?>
            </div>
            <div class="basis-3/12 text-center">
                <a href="https://hub.docker.com/<?php echo($container->repository->namespace === "library" ? "_" : "r/" . $container->repository->namespace) ?>/<?php echo $container->repository->repository ?>/tags"
                   target="_blank">
                    <span class="rounded-xl text-white p-1 px-4 text-sm"
                          style="background-color: <?php echo $statesColors[$state] ?>"><?php echo $statesLabels[$state] ?></span>
                </a>
            </div>
            <div class="basis-3/12 text-center">
                <?php echo($container->repository->lookupDate !== null ? $container->repository->lookupDate : "pending") ?>
            </div>
        </div>
    <?php } ?>

</div>
