<?php
$myFavorite = !empty($_COOKIE['favorite']) ? unserialize($_COOKIE['favorite']) : array();

$imageConfig = curl_init();
curl_setopt($imageConfig, CURLOPT_URL, 'http://api.themoviedb.org/3/configuration?api_key=d040b938a74514398492c6c5acd6179f');
curl_setopt($imageConfig, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($imageConfig, CURLOPT_HEADER, FALSE);
curl_setopt($imageConfig, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
$imageResponse = curl_exec($imageConfig);
curl_close($imageConfig);
$imageURL = json_decode($imageResponse, true);

$pageNo = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$page = intval($pageNo);
$page_size = 20;
$total_records = $pageNo;
$total_pages = 20;

if ($page > $total_pages) {
    $page = $total_pages;
}

if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $page_size;
$currentPage = $page;

include 'header.php';
?>
<body>
<main class='site-main'>
    <section class='app-main-content'>
        <div class='top-header-menu'>
            <div class='top-menu'>
                <div class='products-search justify-content-between'>
                    <div class='profile-block'>
                        <a href='index.php'><h1>LOGO</h1></a>
                    </div>
                    <div class='profile-block'>
                        <a class="btn btn-outline-primary" href='javascript:void(0);' onclick='window.history.go(-1); return false;'>Home</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- movie section start -->
        <section class='music-section-main'>
            <div class='container-fluid'>
                <div class='music-section-inner'>
                    <div class='section-header d-flex align-items-center justify-content-between mb-3'>
                        <h1>My Favorite</h1>
                    </div>
                    <div class='row'>
                        <?php
                        if (isset($myFavorite) && !empty($myFavorite)) :
                            foreach ($myFavorite as $id) :
                                if (!empty($id)) :
                                    $getMovie = curl_init();
                                    curl_setopt($getMovie, CURLOPT_URL, 'https://api.themoviedb.org/3/movie/' . $id . '?api_key=d040b938a74514398492c6c5acd6179f&language=en-US');
                                    curl_setopt($getMovie, CURLOPT_RETURNTRANSFER, TRUE);
                                    curl_setopt($getMovie, CURLOPT_HEADER, FALSE);
                                    curl_setopt($getMovie, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
                                    $movieResponse = curl_exec($getMovie);
                                    curl_close($getMovie);
                                    $movieData = json_decode($movieResponse, true);
                                    ?>
                                    <div class='col-lg-3 col-md-4 col-sm-6'>
                                        <div class='music-sec-box shadow'>
                                            <a href='overview.php?id=<?php echo $movieData[ 'id' ]; ?>' class='font-weight-normal'>
                                            <div class='music-main-img mb-2'>
                                                <?php $image = (!empty($movieData['poster_path'])) ? $imageURL['images']['secure_base_url'].$imageURL['images']['poster_sizes'][4].$movieData['poster_path'] : 'img/image-not-found-2.jpg'; ?>
                                                <img class="img-thumbnail h-100" src="<?php echo $image; ?>" alt="<?php echo $movieData['title']; ?>">
                                            </div>
                                            <div class='name' style='height: 50px;'>
                                                <span class='font-weight-bold text-dark'><?php echo $movieData[ 'title' ]; ?></span>
                                            </div>
                                            </a>
                                            <div class='share-actions'>
                                                <div class='row'>
                                                    <div class='col-3'>
                                                        <a class='btn btn-danger' href='javascript:void(0);' onclick="remove_favorite(<?php echo $movieData[ 'id' ]; ?>)">
                                                            <i class='far fa-heart'></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div><hr>

                                            <div class='entity-info'>
                                                <div class='row'>
                                                    <div class='col-sm-6 col-2'>
                                                        <div class='calendar'>
                                                            <i class='far fa-calendar-alt'></i>&nbsp
                                                            <span><?php echo (!empty($movieData[ 'release_date' ])) ? $movieData[ 'release_date' ] : 'NA'; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class='col-sm-6 col-2'>
                                                        <div class='time'>
                                                            <i class='fas fa-language'></i>&nbsp
                                                            <span><?php echo (!empty($movieData[ 'original_language' ])) ? strtoupper($movieData[ 'original_language' ]) : 'NA'; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class='col-sm-6 col-2'>
                                                        <div class='headphones'>
                                                            <i class='fas fa-star'></i>&nbsp
                                                            <span><?php echo (!empty($movieData[ 'vote_average' ])) ? number_format($movieData[ 'vote_average' ], 1) : 'NA'; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php endif; endforeach; ?>
                            <?php if (count($myFavorite) > 20 && $total_pages) : ?>
                            <div class="pagination justify-content-center">
                                <ul class='pagination'>
                                    <li class='page-item'><a class='page-link' href='index.php?page=<?php echo $page-1; ?>'>Previous</a></li>
                                    <?php
                                    $show = 0;
                                    for ($i = $currentPage; $i <= $total_pages; $i++) {
                                        $show++;
                                        if (($show < 5) || ($total_pages == $i)) {
                                            echo "<li class='page-item'><a class='page-link' href='index.php?mode=1&page=" . $i . "'>" . $i . '</a></li>';
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
<script src='js/custom.js?v=1234'></script>
</body>
</html>