<?php
$imageConfig = curl_init();
curl_setopt($imageConfig, CURLOPT_URL, 'http://api.themoviedb.org/3/configuration?api_key=d040b938a74514398492c6c5acd6179f');
curl_setopt($imageConfig, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($imageConfig, CURLOPT_HEADER, FALSE);
curl_setopt($imageConfig, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
$imageResponse = curl_exec($imageConfig);
curl_close($imageConfig);
$imageURL = json_decode($imageResponse, true);

if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST' || !empty($_REQUEST['id'])) :
    $getMovie = curl_init();
    curl_setopt($getMovie, CURLOPT_URL, 'https://api.themoviedb.org/3/movie/'.$_REQUEST['id'].'?api_key=d040b938a74514398492c6c5acd6179f&language=en-US');
    curl_setopt($getMovie, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($getMovie, CURLOPT_HEADER, FALSE);
    curl_setopt($getMovie, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
    $movieResponse = curl_exec($getMovie);
    curl_close($getMovie);
    $movie = json_decode($movieResponse, true);
endif;

$myFavorite = !empty($_COOKIE['favorite']) ? unserialize($_COOKIE['favorite']) : array();

include 'header.php';
?>
<body>
<!-- main screen start -->
<main class="site-main">
    <section class="app-main-content">
        <div class='top-header-menu'>
            <div class='top-menu'>
                <div class='products-search justify-content-between'>
                    <div class='profile-block'>
                        <a href='index.php'><h1>LOGO</h1></a>
                    </div>
                    <div class='profile-block'>
                        <a class='btn btn-outline-primary' href='index.php'>Home</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- music details section start -->

        <section class="musicdetails-section">
            <div class="container-fluid">
                <div class='section-header d-flex align-items-center justify-content-between'>
                    <h1>Overview</h1>
                </div>
                <div class="musicdetails-box">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="music-profile-img">
                                <?php $image = (!empty($movie['poster_path'])) ? $imageURL['images']['secure_base_url'].$imageURL['images']['poster_sizes'][4].$movie['poster_path'] : 'img/image-not-found-2.jpg'; ?>
                                <img class="h-100 img-thumbnail" src="<?php echo $image; ?>">
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="music-detail-right">
                                <div class="songs-detail">
                                    <a class="btn btn-outline-dark mb-3" href="javascript:void(0);" onclick="window.history.go(-1); return false;"><i class='fa fa-chevron-left'></i></a>
                                    <h1><?php echo $movie['title']; ?></h1>
                                    <div class='category-hastag justify-content-start'>
                                        <?php foreach ($movie['genres'] as $category) : ?>
                                            <a class='entity-genres'><?php echo $category['name']; ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="overview">
                                        <span><?php echo $movie['overview']; ?></span>
                                    </div>
                                    <div class="homepage mt-3">
                                        <span class="font-weight-bold">Home page : </span>
                                        <?php if (!empty($movie['homepage'])) : ?>
                                            <a class="font-weight-normal" href="<?php echo $movie['homepage']; ?>" target="_blank"><?php echo $movie['homepage']; ?></a>
                                        <?php else: ?>
                                            <span>NA</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (in_array($movie['id'], $myFavorite)){ ?>
                                        <a class='btn btn-danger mt-3' href='javascript:void(0);' onclick="remove_favorite(<?php echo $movie[ 'id' ]; ?>)">
                                            <i class='far fa-heart'></i>
                                        </a>
                                    <?php }else{?>
                                        <a class='btn btn-success mt-3' href="javascript:void(0);" id="myButton" onclick="add_favorite(<?php echo $movie['id']; ?>)">
                                            <i class='far fa-heart'></i>
                                        </a>
                                    <?php } ?>
                                </div>
                                <div class="row py-4">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="songs-desc">
                                            <p><i class="far fa-calendar-alt"></i> <?php echo (!empty($movie['release_date'])) ? $movie['release_date'] : 'NA'; ?></p>
                                            <p><i class="fas fa-language"></i> <?php echo (!empty($movie['original_language'])) ? strtoupper($movie['original_language']) : 'NA'; ?></p>
                                            <p><i class="fas fa-star"></i> <?php echo (!empty($movie['vote_average'])) ? number_format($movie['vote_average'],1) : 'NA'; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- music details section end  -->
    </section>
</main>
<!-- main screen end -->
<!-- JavaScript Library -->
<script src="js/jquery.min.js"></script>

<!-- Popper JS and Bootstrap JS -->
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/custom.js"></script>

</body>

</html>