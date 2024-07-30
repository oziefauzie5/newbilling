@extends('layout.user')
@section('content')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
          <section class="content ">
            <div class="card">
            <div class="card-body">
              {{-- <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap"> --}}
                  <div class="table-responsive">
                    <table id="table" class=" table  text-nowrap "  >
              <thead class="text-center">
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Debet</th>
                  <th>Kredit</th>
                  <th>Saldo</th>

                </tr>
              </thead>
              <tbody>
                  @foreach ($mutasi as $d )
                  <tr>
                   <td class="text-center">{{$loop->iteration}}</td>
                   <td>{{ date('d-m-Y H:m:s', strtotime($d->tgl_trx ))}}</td>
                    <td class="text-center">{{ $d->mt_kategori }}</td>
                    <td >{{ $d->mt_deskripsi }}</td>
                    <td class="text-right">Rp. {{ number_format($d->mt_kredit) }}</td>
                    <td class="text-right">Rp. {{ number_format($d->mt_debet) }}</td>
                    <td class="text-right">Rp. {{ number_format($d->mt_saldo) }}</td>
                </tr>
                @endforeach
            </tbody>
         </table>

        </div>
            </div>
          
          <div class="form-group row">
          <div class="col-sm-6">
            <a href="{{ URL::previous() }}" class="btn btn-block btn-primary text-light">Kembali</a>
          </div>
          <div class="col-sm-6">
            <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#exampleModal">Export PDF</button>
            <!-- Button trigger modal -->

              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">EXPORT MUTASI PDF</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('admin.biller.export.mutasi')}}" method="POST">
                        @csrf
                        @method('POST');
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Dari Tanggal</label>
                          <input  type="date" class="form-control" name="start_date" >
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Sampai Tanggal</label>
                          <input type="date" class="form-control"  name="end_date" >
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Export</button>
                    </form>
                    </div>
                  </div>
                </div>
</div>
          </div>
          </div>
     </div>
     </div>
          </section>


</div>
</div>

@endsection
