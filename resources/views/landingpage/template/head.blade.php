<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" integrity="sha512-8E7p4F0gO3bXWMI5FtQzzL5H3wSpGQg0ewdqtTK8blDtJ7yIe6he --}}


<!-- Vendor CSS Files -->
<link href="{{asset('home/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('home/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
<link href="{{asset('home/vendor/aos/aos.css')}}" rel="stylesheet">
<link href="{{asset('home/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
<link href="{{asset('home/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<!-- Main CSS File -->
<link href="{{asset('home\css/main.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- =======================================================
* Template Name: Dewi ->Modify Farhan
* Template URL: https://bootstrapmade.com/dewi-free-multi-purpose-html-template/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
======================================================== -->
<style>
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding: 20px 0;
        margin: 20px 0;
        list-style: none;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0; /* Mengatur garis timeline ke kiri */
        width: 4px;
        height: 100%;
        background: #dee2e6;
    }
    
    .timeline-item {
        display: flex;
        margin-bottom: 20px;
        position: relative;
        justify-content: flex-start; /* Mengatur item untuk start ke kiri */
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        left: -20px; /* Memindahkan ikon ke kiri */
        z-index: 2;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        width: 95%;
        margin-left: 10px; /* Memberikan jarak antara konten dan garis */
    }
    
    .timeline-content::before {
        content: '';
        position: absolute;
        top: 20px;
        width: 10px;
        height: 10px;
        background: #f8f9fa;
        border: 4px solid #dee2e6;
        border-radius: 50%;
        left: -14px;
    }
    
    .timeline-content h5,
    .timeline-content h4 {
        margin-bottom: 10px;
        font-size: 1.2rem;
        color: #333;
    }
    
    .timeline-content p {
        margin: 0;
        color: #6c757d;
    }
    
    /* Menyembunyikan ikon bulat pada layar kecil */
    @media (max-width: 992px) {
        .timeline-icon {
            display: none; /* Menyembunyikan ikon pada perangkat kecil */
        }
    
        .timeline-content {
            width: 100%;
            margin-left: 0;
        }
    }
    </style>
    


@stack('css')