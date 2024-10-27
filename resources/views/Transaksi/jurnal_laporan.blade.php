@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0" ><span>Rp. {{number_format($kredit)}}</span> </div>
            <div class="text-muted mb-3">PEMASUKAN &nbsp; <i class="fas fa-eye btn" aria-hidden="true"></i></div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0"><span>Rp. {{number_format($debet)}}</span></div>
            <div class="text-muted mb-3">PENGELUARAN &nbsp; <i class="fas fa-eye btn" aria-hidden="true"></i></div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <div class="card-body p-3 text-center">
            <div class="h2 m-0"><span>Rp. {{number_format($kredit-$debet)}}</span></div>
            <div class="text-muted mb-3">SALDO &nbsp; <i class="fas fa-eye btn" aria-hidden="true"></i></div>
          </div>
        </div>
      </div>
    </div>

    
   
    <div class="row ">
      
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <div class="alert-title"><h4>Gagal!!</h4></div>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
          </div> 
        @endif
        <a href="{{route('admin.lap.jurnal_print',['id'=>$lap_id])}}" target="blank"><button type="button" class="btn btn-sm btn-primary">
                        <i class="fa fa-print"></i>&nbsp;PRINT
                      </button></a>
                      <hr>
<form >
<div class="row mb-1">

                <div class="col-4">
                  <select name="kategori" class="custom-select custom-select-sm">
                    @if($kategori)
                    <option value="{{$kategori}}" selected>{{$kategori}}</option>
                    @endif
                    <option value="">ALL DATA</option>
                <option value="PSB">PSB</option>
                <option value="MARKETING">MARKETING</option>
                <option value="KOMSUMSI">KOMSUMSI</option>
                <option value="IKLAN & PROMOSI">IKLAN & PROMOSI</option>
                <option value="PERLENGKAPAN">PERLENGKAPAN</option>
                <option value="PERALATAN">PERALATAN</option>
                <option value="ATK">ATK</option>
                <option value="LISTRIK">LISTRIK</option>
                <option value="ENTERTEMENT">ENTERTEMENT</option>
                <option value="PERBAIKAN & PERAWATAN">PERBAIKAN & PERAWATAN</option>
                <option value="KEAMANAN DAN KEBERSIHAN">KEAMANAN DAN KEBERSIHAN</option>
                <option value="IURAN & BERLANGGANAN">IURAN & BERLANGGANAN</option>
                <option value="SEWA BANGUNAN">SEWA BANGUNAN</option>
                <option value="INFRASTRUKTUR">INFRASTRUKTUR</option>
                <option value="PERALATAN KANTOR">PERALATAN KANTOR</option>
                <option value="BIAYA ADMIN">BIAYA ADMIN</option>
                <option value="LAIN-LAIN">LAIN-LAIN</option>
                  </select>
                </div>
                <div class="col-4">
                  <input type="text" name="q" class="form-control form-control-sm" value="{{$q}}" placeholder="Cari Data">
                </div>
                  <div class="col-4">
                    <button type="submit" class="btn btn-block btn-dark btn-sm">Cari
                  </div>
                  </form>
                  </div>
          <div class="table-responsive">
          <table class="display table table-striped table-hover " >
              <thead>
                <tr>
                  <th>#</th>
               
                  <th>BUKTI TRX</th>
                  <th>TANGGAL</th>
                  <th>KATEGORI</th>
                  <th>KETERANGAN</th>
                  <th>URAIAN</th>
                  <th>QTY</th>
                  <th>KREDIT</th>
                  <th>DEBET</th>
                  <th>SALDO</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($jurnal as $d)
                <tr>
                  <td>{{$loop->iteration}}</td>
                      
                      <td>   <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bukti_trx{{$d->id}}">
                       File
                      </button>
                      <!-- Modal -->
                      <div class="modal fade" id="bukti_trx{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                       <div class="modal-dialog">
                         <div class="modal-content">
                           <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer</h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                             </button>
                           </div>
                           <div class="modal-body">
                           <img class="rounded mx-auto d-block" src="{{ asset('storage/bukti-transaksi/'.$d->jurnal_img) }}" width="300" alt="">
                           </div>
                           <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                             @if($d->jurnal_img)
                             <a href="{{route('admin.lap.download_file',['id'=>$d->jurnal_img])}}"><button class="btn btn-secondary">{{$d->jurnal_img}}Download</button></a>
                             @else
                             <button class="btn btn-secondary" disabled>Download</button>
                             @endif
                           </div>
                         </div>
                       </div>
                     </div>
                     <!-- ---end modal---- -->
                    </td>
                   
                    <td>{{date('d-m-Y H:m:s',strtotime($d->created_at))}}</td>
                      <td>{{$d->jurnal_kategori}}</td>
                      <td>{{$d->jurnal_keterangan}}</td>
                      <td>{{$d->jurnal_uraian}}</td>
                      <td>{{$d->jurnal_qty}}</td>
                      <td>{{number_format($d->jurnal_kredit)}}</td>
                      <td>{{number_format($d->jurnal_debet)}}</td>
                      <td>{{number_format($d->jurnal_saldo)}}</td>
                    </tr>
                    @endforeach
              </tbody>
            </table>
            <div class="pull-left">
              Showing
              {{$jurnal->firstItem()}}
              to
              {{$jurnal->lastItem()}}
              of
              {{$jurnal->total()}}
              entries
            </div>
            <div class="pull-right">
              {{ $jurnal->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection