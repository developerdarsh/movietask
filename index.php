<?php
$imageConfig = curl_init();
curl_setopt($imageConfig, CURLOPT_URL, 'http://api.themoviedb.org/3/configuration?api_key=d040b938a74514398492c6c5acd6179f');
curl_setopt($imageConfig, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($imageConfig, CURLOPT_HEADER, FALSE);
curl_setopt($imageConfig, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
$imageResponse = curl_exec($imageConfig);
curl_close($imageConfig);
$imageURL = json_decode($imageResponse, true);
$pageNo = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;

$popular = curl_init();
curl_setopt($popular, CURLOPT_URL, 'https://api.themoviedb.org/3/movie/popular?api_key=d040b938a74514398492c6c5acd6179f&language=en-US&page='.$pageNo.'');
curl_setopt($popular, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($popular, CURLOPT_HEADER, FALSE);
curl_setopt($popular, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
$popularResponse = curl_exec($popular);
curl_close($popular);
$movies = json_decode($popularResponse, true);

$page = intval($pageNo);
$page_size = 20;
$total_records = $pageNo;
$total_pages = ($movies['total_pages'] > 500) ? 500 : $movies['total_pages'];

if ($page > $total_pages) {
    $page = $total_pages;
}

if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $page_size;
$currentPage = $page;

$myFavorite = !empty($_COOKIE['favorite']) ? unserialize($_COOKIE['favorite']) : array();
include 'header.php';
?>
<body>
<main class='site-main'>
    <section class='app-main-content'>
        <div class='top-header-menu'>
            <div class='top-menu'>
                <div class='products-search'>
                    <div class='profile-block'>
                        <a href="index.php"><h1>LOGO</h1></a>
                    </div>
                    <div class='products-search__box'>
                        <form method="get" action="search.php" autocomplete="off">
                            <div class='input-group'>
                                <input type='text' required="required" name="search" class='form-control products-search__input' placeholder='Search..'>
                                <input type="hidden" name="page" value="1">
                                <div class='input-group-append'>
                                    <button class='btn btn-secondary' type='submit' style='border-radius: 0 26px 26px 0;'>
                                        <i class='fa fa-search'></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <button type='submit' class='products-search__lens'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='21' height='20' viewBox='0 0 21 20'>
                                <path fill='#2E2C37' fill-rule='evenodd' d='M20.0081 19.7732c-.8402.4198-1.6003.2399-2.2705-.3998-1.7004-1.649-3.4308-3.2982-5.1712-4.9973-4.421 1.999-8.502 1.2294-10.9825-1.999-2.3305-3.0183-2.0605-7.196.6202-9.9246C4.8447-.246 9.2057-.7858 12.4864 1.1732c1.6504.9795 2.8006 2.3587 3.4208 4.0978.8202 2.2988.46 4.5176-.8002 6.6364 1.7004 1.6192 3.3808 3.1983 5.0011 4.7775.3501.3298.7002.7396.8202 1.1693.23.8196-.1 1.5192-.9002 1.929l-.02-.01zM8.1554 2.2026c-3.1707.02-5.8013 2.5187-5.8313 5.537 0 3.0784 2.6306 5.577 5.8813 5.5671 3.2608 0 5.8214-2.4687 5.8015-5.597-.0402-3.0384-2.6507-5.497-5.8415-5.497l-.01-.01zm-.33 2.9984C6.605 5.501 5.9048 6.3504 5.4647 7.4798c-.1.2499-.37.6796-1.2003.4498-.48-.13-.47-.5497-.5-.8496-.1001-1.2993 2.2504-3.518 3.9008-3.518.3 0 .6001.3797.6402.6796.06.2998.05.8695-.4602.9794l-.02-.02z'></path>
                            </svg>
                        </button>
                    </div>
                    <div class='profile-block'>
                        <a class="btn btn-outline-primary" href="my-favorite.php">My Favorite</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- movie section start -->
        <section class='music-section-main'>
            <div class='container-fluid'>
                <div class='music-section-inner'>
                    <div class='section-header d-flex align-items-center justify-content-between mb-3'>
                        <h1>Movies & TVs Shows</h1>
                        <?php if (isset($_REQUEST['search'])) : ?>
                            <span>Search For "<?php echo $_REQUEST['search']; ?>"</span>
                        <?php endif; ?>
                    </div>
                    <div class='row'>
                        <?php if (isset($movies) && !empty($movies)) :
                            foreach($movies['results'] as $movieData) : ?>
                            <div class='col-lg-3 col-md-4 col-sm-6'>
                                <div class='music-sec-box shadow'>
                                    <a href="overview.php?id=<?php echo $movieData['id']; ?>" class='font-weight-normal'>
                                        <div class='music-main-img mb-2'>
                                            <?php $image = (!empty($movieData['poster_path'])) ? $imageURL['images']['secure_base_url'].$imageURL['images']['poster_sizes'][4].$movieData['poster_path'] : 'img/not-found.png'; ?>
                                            <img class="img-thumbnail" src="<?php echo $image; ?>" alt="<?php echo $movieData['title']; ?>">
                                        </div>
                                        <div class="name" style="height: 50px;">
                                            <span class="font-weight-bold text-dark"><?php echo $movieData['title']; ?></span>
                                        </div>
                                    </a>
                                    <div class='share-actions'>
                                        <div class='row'>
                                            <div class='col-3'>
                                                <?php if (in_array($movieData['id'], $myFavorite)){ ?>
                                                    <a class='btn btn-danger' href='javascript:void(0);' onclick="remove_favorite(<?php echo $movieData[ 'id' ]; ?>)">
                                                        <i class='far fa-heart'></i>
                                                    </a>
                                                <?php }else{?>
                                                    <a class='btn btn-success' href="javascript:void(0);" id="myButton" onclick="add_favorite(<?php echo $movieData['id']; ?>)">
                                                        <i class='far fa-heart'></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div><hr>

                                    <div class='entity-info'>
                                        <div class='row'>
                                            <div class='col-sm-6 col-2'>
                                                <div class='calendar'>
                                                    <i class='far fa-calendar-alt'></i>&nbsp
                                                    <span><?php echo (!empty($movieData['release_date'])) ? $movieData['release_date'] : 'NA'; ?></span>
                                                </div>
                                            </div>
                                            <div class='col-sm-6 col-2'>
                                                <div class='time'>
                                                    <i class='fas fa-language'></i>&nbsp
                                                    <span><?php echo (!empty($movieData['original_language'])) ? strtoupper($movieData['original_language']) : 'NA'; ?></span>
                                                </div>
                                            </div>
                                            <div class='col-sm-6 col-2'>
                                                <div class='headphones'>
                                                    <i class='fas fa-star'></i>&nbsp
                                                    <span><?php echo (!empty($movieData['vote_average'])) ? number_format($movieData['vote_average'],1) : 'NA'; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($movies['total_pages']) : ?>
                            <div class="col mb-5">
                                <ul class='pagination justify-content-center'>
                                    <li class='page-item'><a class='page-link' href='index.php?page=<?php echo $page-1; ?>'>Previous</a></li>
                                    <?php
                                    $show = 0;
                                    for ($i = $currentPage; $i <= $total_pages; $i++) {
                                        $show++;
                                        if (($show < 5) || ($total_pages == $i)) {
                                            echo "<li class='page-item'><a class='page-link' href='index.php?page=" . $i . "'>" . $i . '</a></li>';
                                        } else {
                                            if ($show < 6){
                                                echo "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
                                            }
                                        }
                                        ?>
                                    <?php } ?>
                                    <li class='page-item'><a class='page-link' href='index.php?page=<?php echo $page+1; ?>'>Next</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php else: ?>
                            <div class='col-lg-2 col-md-4 col-sm-6'>
                                No Data Available
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- music section end -->
    </section>
</main>
<!-- main screen end -->
<!-- JavaScript Library -->
<script src='js/jquery.min.js'></script>
<!-- Popper JS and Bootstrap JS -->
<script src='js/popper.min.js'></script>
<script src='js/bootstrap.js'></script>
<script src='js/custom.js?v=123'></script>
</body>
</html>
