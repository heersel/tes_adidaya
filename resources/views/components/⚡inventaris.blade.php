<?php
    use Livewire\Component;
    use App\Models\Inventaris;

    new class extends Component
    {
        public $showModal = false;
        public $nama, $kategori, $stok, $harga;
        public $search = '';
        public $editId = null;

        public function openModal()
        {
            $this->reset(['editId', 'nama', 'kategori', 'stok', 'harga']);
            $this->resetValidation();
            $this->showModal = true;
        }

        public function closeModal()
        {
            $this->reset(['editId', 'nama', 'kategori', 'stok', 'harga']);
            $this->resetValidation();
            $this->showModal = false;
        }

        public function save()
        {
            $this->validate([
                'nama' => 'required|min:3',
                'kategori' => 'required',
                'stok' => 'required|integer|min:0',
                'harga' => 'required|integer|min:0',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'kategori.required' => 'Kategori wajib diisi.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Stok harus berupa angka.',
                'stok.min' => 'Stok tidak boleh negatif.',
                'harga.required' => 'Harga wajib diisi.',
                'harga.integer' => 'Harga harus berupa angka.',
            ]);

            if ($this->editId) {
                Inventaris::findOrFail($this->editId)->update([
                    'nama' => $this->nama,
                    'kategori' => $this->kategori,
                    'stok' => $this->stok,
                    'harga' => $this->harga,
                ]);
            } else {
                Inventaris::create([
                    'nama' => $this->nama,
                    'kategori' => $this->kategori,
                    'stok' => $this->stok,
                    'harga' => $this->harga,
                ]);
            }

            $this->closeModal();
        }

        public function with()
        {
            return [
                'data' => Inventaris::query()
                    ->when(filled($this->search), fn ($q) =>
                        $q->where('nama', 'like', "%{$this->search}%")
                    )
                    ->latest()
                    ->get()
            ];
        }

        public function edit($id)
        {
            $data = Inventaris::findOrFail($id);

            $this->editId = $id;
            $this->nama = $data->nama;
            $this->kategori = $data->kategori;
            $this->stok = $data->stok;
            $this->harga = $data->harga;

            $this->showModal = true;
        }

        public function delete($id)
        {
            Inventaris::findOrFail($id)->delete();
        }
    };
?>

@section('title', 'Sistem Manajemen Inventaris Sederhana')

<div>
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-3 gap-2">
            <h3 class="mb-0 me-auto">Data Inventaris</h3>

            <input 
                type="text" 
                class="form-control w-25" 
                placeholder="Cari nama produk..."  
                wire:model.live.debounce.500ms="search"
            >

            <button class="btn btn-primary" wire:click="openModal">
                + Tambah
            </button>
        </div>

        <div wire:loading class="mb-2 text-muted">
            Mencari...
        </div>

        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" wire:click="edit({{ $item->id }})">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger" wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin ingin menghapus data ini?')"
                            >
                                Delete
                            </button>
                        </td>                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Data tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($showModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Inventaris</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>

                        <div class="modal-body">
                            
                            <div class="mb-2">
                                <label>Nama</label>
                                <input type="text" class="form-control" wire:model="nama">
                                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-2">
                                <label>Kategori</label>
                                <input type="text" class="form-control" wire:model="kategori">
                                @error('kategori') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-2">
                                <label>Stok</label>
                                <input type="number" class="form-control" wire:model="stok">
                                @error('stok') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-2">
                                <label>Harga</label>
                                <input type="number" class="form-control" wire:model="harga">
                                @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button class="btn btn-primary" wire:click="save">Simpan</button>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

