
  <link rel="stylesheet" href="{{asset('mahasiswa/css/styles.min.css')}}" />
  <style>
    .timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    margin: 20px 0;
    padding-left: 40px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ccc;
}

.timeline-content {
    background: #f9f9f9;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.completed {
    background: #d4edda; /* Warna hijau untuk status selesai */
}

.completed .timeline-content {
    background: #c3e6cb; /* Warna hijau muda untuk konten selesai */
}
  </style>
  @stack('styles')