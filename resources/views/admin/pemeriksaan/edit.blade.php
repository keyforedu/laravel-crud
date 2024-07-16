@extends('master')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Pemeriksaan</h1>
            <div class="card border-0 shadow rounded">
            @include('partial.dangeralert')
            
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <!-- token form -->
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="pasien">Pasien</label>
                            <input type="hidden" name="no_transaksi_pemeriksaan" value="">
                            <select id="pasien" name="pasien" class="form-control @error('pasien') is-invalid @enderror" required>
                                <option value="" selected>- Pilih -</option>
                                @foreach ($datapasien as $dp)
                                <option value="{{ $dp->id }}" {{ old('pasien')?? $pemeriksaan->idPasien == $dp->id ? 'selected':''  }}>{{ $dp->nama }}</option>
                                @endforeach
                                
                            </select>

                            <!-- error message untuk pasien -->
                            @error('pasien')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dokter">Dokter</label>
                            <label for="dokter">Dokter</label>
                            <select id="dokter" name="dokter" class="form-control @error('dokter') is-invalid @enderror" required>
                                <option value="" selected>- Pilih -</option>
                                @foreach($datadokter as $dd)
                                <option value="{{ $dd->id }}" {{ old('dokter')?? $pemeriksaan->idDokter == $dd->id ? 'selected':''  }}>{{ $dd->nama }}</option>
                                @endforeach
                            </select>
                        

                            <!-- error message untuk dokter -->
                            @error('dokter')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggalPeriksa">Tanggal Periksa</label>
                            <input type="date" class="form-control @error('tanggalPeriksa') is-invalid @enderror"
                                   name="tanggalPeriksa" value="{{ old('tanggalPeriksa', $pemeriksaan->tanggalPeriksa) }}" required>

                            <!-- error message untuk tanggal -->
                            @error('tanggalPeriksa')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password">Keluhan</label>
                            <textarea name="keluhan" id="keluhan" class="form-control @error('keluhan') is-invalid @enderror" required>{{ old('keluhan', $pemeriksaan->keluhan) }}</textarea>

                            <!-- error message untuk keluhan -->
                            @error('keluhan')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">File Lampiran</label>
                            <label for="" class="form-label">File Lampiran</label>
                            <input type="file" id="fileLampiran" name="fileLampiran" value="{{ old('fileLampiran') }}" class="form-control @error('fileLampiran') is-invalid @enderror" required>

                            <!-- error message untuk password -->
                            @error('fileLampiran')
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-md btn-primary">Save</button>
                        <a href="" class="btn btn-md btn-secondary">back</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
