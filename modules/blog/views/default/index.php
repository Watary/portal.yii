<div class="blog">
    <?php for($i = 0; $i < 5; $i++){ ?>
        <div class="blog-article" style="">
            <div class="image" style="background-image: url('https://cdn.pixabay.com/photo/2017/08/30/01/05/milky-way-2695569_960_720.jpg')">1</div>
            <div class="article-body">
                <span class="article-info">
                    <span>Author: <a href="#">Alexander Pierce</a></span>
                    <span>Category: <a href="#">WEB development</a></span>
                    <span><i class="far fa-calendar-alt"></i> 06-07-2019 17:19</span>
                    <span>Tags: <a href="#">new</a>, <a href="#">post</a>, <a href="#">for</a></span>
                    <span class="badge badge-secondary"><i class="far fa-comment-dots"></i> 70</span>
                    <span class="badge badge-secondary"><i class="fas fa-eye"></i> 500</span>
                    <span class="badge badge-secondary"><i class="fas fa-eye"></i> 1000</span>
                </span>
                <h3 class="article-title">Card title</h3>
                <p class="article-text">Some quick example text to build on the card title and make up the bulk of the card's content.Some quick example text to build on the card title and make up the bulk of the card's content.Some quick example text to build on the card title and make up the bulk of the card's content.Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
            <a href="#" class="btn btn-lg btn-block">Show more</a>
        </div>
    <?php } ?>
</div>

<nav aria-label="Page navigation example" style="text-align: center">
    <ul class="pagination blog-pagination">
        <li class="page-item">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
        </li>

        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item disabled"><span class="page-link">...</span></li>
        <li class="page-item"><a class="page-link" href="#">34</a></li>
        <li class="page-item"><a class="page-link" href="#">35</a></li>
        <li class="page-item active"><span class="page-link">36</span></li>
        <li class="page-item"><a class="page-link" href="#">37</a></li>
        <li class="page-item"><a class="page-link" href="#">38</a></li>
        <li class="page-item disabled"><span class="page-link">...</span></li>
        <li class="page-item"><a class="page-link" href="#">76</a></li>

        <li class="page-item">
            <a class="page-link" href="#">&raquo;</a>
        </li>
    </ul>
</nav>