@extends('layout.main')
@section('content')

<div class="content">
  <div class="page-inner">
  <div class="card">
    <div class="invoice p-3">
            <div class="row">
              <div class="col-12">
                <h4>
                  {{$invoice->reg_nolayanan}}
                  <small class="float-right">Date: {{date("d/m/Y", strtotime($invoice->inv_tgl_tagih))}}</small>
                </h4>
              </div>
            </div>
            <div class="row m-3">
              <div class="col-sm-4">
                <address>
                  <b><strong>{{$invoice->input_nama}}</strong></b><br>
                  {{$invoice->input_alamat_pasang}}<br>
                  {{$invoice->input_hp}}<br>
                  {{$invoice->input_email}}
                </address>
                @if($invoice->inv_admin == 'SYSTEM')
                <b>Counter : </b> <span ><strong> SYSTEM</strong></span><br>
                @else
                <b>Counter : </b> <span ><strong> {{$nama_admin}}</strong></span><br>
                @endif
              </div>
              <div class="col-sm-4">

              </div>
              <div class="col-sm-4">
                <b >Invoice : INV- <b data-toggle="modal" data-target="#update_inv{{$invoice->inv_id}}">{{$invoice->inv_id}}</b></b><br>
                <!-- Modal -->
<div class="modal fade" id="update_inv{{$invoice->inv_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Invoice {{$invoice->inv_id}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-group">
        <form action="{{route('admin.inv.update_inv',['inv_id'=>$invoice->inv_id])}}" method="post">
          @csrf
          @method('PUT')
          <label>Tanggal Jatuh Tempo</label>
          <input type="text" class="form-control" name="tgl_jth_tempo" value="{{date('d-m-Y',strtotime($invoice->inv_tgl_jatuh_tempo))}}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
                <!-- <b>Invoice : INV-{{$invoice->inv_id}}</b><br> -->
                <br>
                <b>Jatuh Tempo</b> {{date('d-m-Y',strtotime($invoice->inv_tgl_jatuh_tempo))}}<br>
                @if($invoice->inv_status != 'PAID')
                <b>Tanggal Isolir</b> {{date('d-m-Y',strtotime($invoice->inv_tgl_isolir))}}<br>
                <b>Status : </b> <span class="text-danger"><strong> {{$invoice->inv_status}}</strong></span><br>
                @else
                <b>Tanggal Isolir</b> {{date('d-m-Y',strtotime($invoice->inv_tgl_isolir))}}<br>
                <b>Tanggal Bayar</b><b data-toggle="modal" data-target="#update_bayar{{$invoice->inv_id}}">{{date('d-m-Y',strtotime($invoice->inv_tgl_bayar))}}</b> <br>
                 <!-- Modal -->
<div class="modal fade" id="update_bayar{{$invoice->inv_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Tanggal Bayar {{$invoice->inv_id}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-group">
        <form action="{{route('admin.inv.update_tgl_bayar',['inv_id'=>$invoice->inv_id])}}" method="post">
          @csrf
          @method('PUT')
          <label>Tanggal Bayar</label>
          <input type="text" class="form-control" name="tgl_bayar" value="{{date('d-m-Y',strtotime($invoice->inv_tgl_bayar))}}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
      </div>
    </div>
  </div>
</div>
                <b>Metode Bayar : </b> <span ><strong> {{$invoice->inv_payment_method}}</strong></span><br>
                <b>Status : </b> <span class="text-success"><strong> {{$invoice->inv_status}}</strong></span><br>
             
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table">
                  <thead>
                  <tr class="">
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>PPN</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($deskripsi as $desk)
                  <tr>
                    <td>1</td>
                    <td>{{$desk->subinvoice_deskripsi}}</td>
                    <td>{{$desk->subinvoice_qty}}</td>
                    <td class="text-right ">{{number_format($desk->subinvoice_harga)}}</td>
                    <td class="text-right ">{{number_format($desk->subinvoice_ppn)}}</td>
                    <td class="text-right text-bold">{{number_format($desk->subinvoice_total)}}</td>
                    @if($desk->subinvoice_status=='1')
                    <form action="{{route('admin.inv.addons_delete',['id'=>$desk->id,'inv'=>$desk->subinvoice_id,'tot'=>$desk->subinvoice_total])}}" method="POST" >
                    @csrf
                    @method('DELETE')
                      <td><button type="submit" class="btn text-danger btn-sm" > <i class="fas fa-solid fa-trash"></i></button></td>
                    </form>
                    @else
                    <td></td>
                    @endif
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                @if($invoice->inv_status!='PAID')
                <div class="row">
                  <label for="diskon" class="col-2">Diskon</label>
                  <div class="col-6">
                    <input type="text"  id="diskon" class="form-control">
                    <input type="hidden"  id="inv_id" value="{{$invoice->inv_id}}" class="form-control">
                    <input type="hidden"  id="inv_harga" value="{{$sumharga-$sumppn}}" class="form-control">
                    <input type="hidden"  id="inv_jumlah" value="{{$sumharga}}" class="form-control">
                  </div>
                  <div class="col">
                    <button class="btn btn-primary btn-block btn-sm" id="submit_diskon">Diskon</button>
                  </div>
                  <label for="diskon" class="col-8  mt-3 "></label>
                  <div class="col-4">
                    <button class="btn btn-primary btn-block btn-sm mt-3"  data-toggle="modal" data-target="#exampleModal" >Add Ons</button>
                  </div>
                </div>
                @else
                <div class="row">
                  <div class="col">
                       @if($invoice->inv_cabar=='TRANSFER')
               
                    <!-- Button trigger modal -->
                      <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModal">
                        Lihat Bukti Transfer
                      </button>

                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                            <img class="rounded mx-auto d-block" src="{{ asset('storage/bukti-transfer/'.$invoice->inv_bukti_bayar) }}" width="300" alt="">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- ---end modal---- -->
                      @endif
                    
                  </div>
                </div>
                @endif
              </div>

              <div class="col-6">
                <div class="table-responsive">
                  <table class="table" id="Table">
                    <tr>
                      <th style="width:50%">Subtotal:</th>
                      <td>Rp {{number_format($sumharga-$sumppn)}}</td>
                    </tr>
                    <tr>
                      <th>PPN {{$ppnj}}%</th>
                      <td >Rp {{number_format($sumppn)}}</td>
                    </tr>
                    <tr>
                      <th>Diskon</th>
                      <td id="td">Rp {{number_format($invoice->inv_diskon)}}</td>
                    </tr>
                    <tr>
                      <th>Total:</th>
                      <td id="tot">Rp {{number_format($sumharga-$invoice->inv_diskon)}}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="row no-print">
              <div class="col-12">
                <!-- Button trigger modal -->
<button type="button"  data-toggle="modal" data-target="#exampleModalprint" class="btn btn-default btn-sm"><i class="fas fa-print"></i>
  PRINT
</button>

<!-- Modal print -->
<div class="modal fade" id="exampleModalprint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PILIH JENIS KERTAS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.inv.print_inv', ['id'=>$invoice->inv_id]) }}" method="POST">
          @csrf
          @method('PUT')
          <select name="cara_print" id="" class="form-control">
            <option value="1">ROL PAPER HTML</option>
            <option value="2">HVS PAPER HTML</option>
            {{-- <option value="ROL PAPER PDF">ROL PAPER PDF</option> --}}
            {{-- <option value="HVS PAPER PDF">HVS PAPER PDF</option> --}}
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-block">PRINT</button>
        </div>
      </form>
    </div>
  </div>
</div>
                @if($invoice->inv_status!='PAID')
                <button type="button" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#staticBackdrop" id="modal_bayar" ><i class="far fa-credit-card"></i> BAYAR
              </button>
              @else
              <button type="button" class="btn btn-danger float-right btn-sm" data-toggle="modal" data-target="#modal-rolback{{$invoice->inv_id}}"><i class="far fa-credit-card"></i> ROLBACK
              </button>
              <div class="modal fade" id="modal-rolback{{$invoice->inv_id}}">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">ROLLBACK INVOICE</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('admin.inv.rollback', ['id'=>$invoice->inv_id]) }}" method="POST">
                        @csrf
                          @method('PUT')
                        <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">INVOICE</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control"   name="id" value="{{$invoice->inv_id}}">
                        </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputPassword" class="col-sm-4 col-form-label">PELANGGAN</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="inputtext" name="nama" value="{{$invoice->input_nama}}">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputtext" class="col-sm-4 col-form-label">TGL BAYAR</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="inputtext" value="{{$invoice->inv_tgl_bayar}}">
                        </div>
                      </div>
                      <div class="form-group row"> 
                        <label for="inputtext" class="col-sm-4 col-form-label" >ADMIN</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="inputtext" name="admin" value="{{$invoice->inv_admin}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputtext" class="col-sm-4 col-form-label">TOTAL</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="inputtext" name="total" value="{{$invoice->inv_total}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputtext" class="col-sm-4 col-form-label">Metode Pengembalian</label>
                        <div class="col-sm-8">
                         <select name="metode_bayar_rolback" id="metode_bayar_rolback"  class="form-control" required>
                          <option value="">--Pilih Bank--</option>
                          @foreach($akun_all as $b)
                          <option value="{{$b->id.'|'.$b->akun_nama}}">{{$b->akun_nama}}</option>
                          @endforeach
                        </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputtext" class="col-sm-4 col-form-label">STATUS</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" value="{{$invoice->inv_status}}" >
                        </div>
                      </div>
                      <button type="submit" class="btn btn-block btn-danger">ROLLBACK INVOICE</button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
                @endif
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">PILIH CARA BAYAR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <form action="{{ route('admin.inv.payment',['id'=>$invoice->inv_id])}}" method="POST" enctype="multipart/form-data">
                          @csrf
                          @method('PUT')
                       <select name="cabar" id="cabar"  class="form-control" required>
                        <option value="">--Pilih Metode Bayar--</option>
                        <option value="TUNAI">BAYAR TUNAI</option>
                        <option value="TRANSFER">TRANSFER BANK</option>
                       </select>
<br>
                        <select name="tunai" id="tunai"  class="form-control" style="display:none;">
                          @foreach($akun_tunai as $b)
                          <option selected value="{{$b->id.'|'.$b->akun_rekening.'|'.$b->akun_nama}}">{{$b->akun_nama}}</option>
                          @endforeach
                        </select>
                        <select name="transfer" id="transfer"  class="form-control" style="display:none;">
                          <option value="">--Pilih Bank--</option>
                          @foreach($akun as $b)
                          <option value="{{$b->id.'|'.$b->akun_rekening.'|'.$b->akun_nama}}">{{$b->akun_nama}}</option>
                          @endforeach
                        </select>
                        <br>
                        <input type="number" id="jb" name="jumlah_bayar" placeholder="Masukan jumlah pembayaran" class="form-control" style="display:none;">
                        <br>
                        <div class="input-group mb-3" id="bb" style="display:none;">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupFileAddon01">Upload Bukti Trasnfer</span>
                          </div>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="inv_bukti_bayar" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                          </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" >BAYAR</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            {{-- end modal BAYAR --}}
            <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">AddOns</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('admin.inv.addons',['id'=>$invoice->inv_id])}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col">
            <input type="text" class="form-control inv_ppn" name=""  value="{{$ppn->biaya_ppn}}" required readonly>
          </div>
          <div class="col">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" id="persen" name="persen" value="">
                <span class="form-check-sign">PPN</span>
              </label>
            </div> 
          </div>
        </div>

              <div class="row mt-3">
                <div class="col-12">
                  <label for="exampleInputEmail1">Deskripsi</label>
                  <textarea class="form-control" name="Deskripsi"> </textarea>
                </div>
              </div>
              <div class="row">
                      <div class="col-2">
                        <label for="exampleInputEmail1">Qty</label>
                        <input type="text" class="form-control" name="qty" id="qty" value="1" required>
                      </div>
                      <div class="col">
                        <label for="exampleInputEmail1">Harga</label>
                        <input type="text" class="form-control" name="harga" id="harga" required >
                      </div>
                      <div class="col">
                              <label for="exampleInputEmail1">Jumlah</label>
                              <input type="text" class="form-control" name="ppn" id="ppn" required readonly>
                            </div>
                      <div class="col">
                  <label for="exampleInputEmail1">Total</label>
                  <input type="text" class="form-control" name="total" id="total" required readonly>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
            </div>
            </form>
                </div>
              </div>
            </div>

           

          </div>
      </div>
    </div>
  </div>
@endsection
