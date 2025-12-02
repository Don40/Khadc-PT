<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Registrations (No Actions)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <!-- Logo Image -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('images/khadc.jpg') }}" alt="Logo" style="max-width: 100%;">
    </div>
   
    <h2 class="mb-4">Registered Professional Tax Payers</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('registrations.viewown') }}" class="row mb-3">
        <div class="col-md-4 col-sm-12 mb-2">
            <input type="text" name="search" class="form-control" placeholder="Search by name or mobile" value="{{ $search }}">
        </div>
        <div class="col-md-2 col-sm-12 mb-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-md-2 col-sm-12 mb-2">
            <a href="{{ route('registrations.viewown') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>
   
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>File</th>
                    <th>Registered At</th>
                    <th>Range of Annual Income</th>
                    <th>Tax Amount</th>
                     <!-- <th>Action</th>  -->
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $reg)
                    <tr>
                        <td>{{ $reg->user_id }}</td>
                        <td>{{ $reg->name }}</td>
                        <td>{{ $reg->mobile }}</td>
                        <td>
                            @if($reg->file_path)
                                @php
                                    $ext = strtolower(pathinfo($reg->file_path, PATHINFO_EXTENSION));
                                    $fileUrl = asset('storage/' . $reg->file_path);
                                @endphp

                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ $fileUrl }}" alt="Image" class="img-thumbnail" style="max-width: 80px; cursor: pointer;" onclick="openModal('{{ $fileUrl }}')">
                                @elseif($ext === 'pdf')
                                    <a href="{{ $fileUrl }}" target="_blank">View PDF</a>
                                @else
                                    <a href="{{ $fileUrl }}" target="_blank">Download</a>
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $reg->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $reg->income_range }}</td>
                        <td>₹{{ number_format($reg->tax_amount ) }}</td>
                   <!--   <td>-->
                   <!--<form action="{{ route('registrations.delete', $reg->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">-->
                   <!--         @csrf-->
                   <!--         @method('DELETE')-->
                   <!--         <button class="btn btn-sm btn-danger">Delete</button>-->
                   <!--     </form>-->
                   <!-- </td>-->
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No registrations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

            <!-- Pagination -->
            {{ $registrations->withQueryString()->links() }}
                <div class="d-flex justify-content-between align-items-center my-3">
                <div>
                Showing <strong>{{ $registrations->firstItem() }}</strong>
                to <strong>{{ $registrations->lastItem() }}</strong>
                of <strong>{{ $registrations->total() }}</strong> results
                </div>
                <div>
            </div>
        </div>
         <div class="d-flex justify-content-start mt-4">
            <a href="{{ url('/') }}" class="btn btn-dark ">
                ← Back to Home
            </a>
        </div>
    </div>

<!-- JS and Modal -->
<script>
    function openModal(imageUrl) {
        const img = document.getElementById('modalImage');
        img.src = imageUrl;
        const modal = new bootstrap.Modal(document.getElementById('imgModal'));
        modal.show();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Image Modal -->
<div id="imgModal" class="modal fade" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-body text-center p-0">
        <img id="modalImage" src="" alt="Preview" style="width: 100%; height: auto;">
      </div>
    </div>
  </div>
</div>

</body>
</html>
