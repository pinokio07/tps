<div class="row">
  @if (count($errors) > 0)
    <div class="col-12">
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    </div>
  @endif
  @csrf
  <div class="col-md-3">          
    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img class="profile-user-img img-fluid img-circle"
                src="{{$user->getAvatar()}}"
                alt="User profile picture">
        </div>

        @if($user)
          <h3 class="profile-username text-center">
            {{Str::title($user->name)}}
          </h3>

          <p class="text-muted text-center">
            Roles :
            @forelse($user->roles as $role)
              {{$role->name}};
            @empty
              -
            @endforelse
          </p>
        @endif
        @if(!isset($from))
          <div class="form-group mt-2">
            <input type="file" 
                   name="avatar" 
                   id="avatar" 
                   class="form-control" 
                   accept="image/*">
          </div>        
        @endif
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Profile</h3>                
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="form-horizontal">
          <div class="form-group row">
            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <input type="text" 
                     class="form-control" 
                     name="name" 
                     id="inputName" 
                     placeholder="Name" 
                     value="{{ old('name') ?? $user->name ?? ''}}" 
                     required 
                     @isset($from) disabled @endisset>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
              <input type="text" 
                     class="form-control" 
                     name="username" 
                     id="inputUsername" 
                     placeholder="Name" 
                     value="{{ old('username') ?? $user->username ?? ''}}" 
                     required 
                     @isset($from) disabled @endisset>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="email" 
                     class="form-control" 
                     name="email" 
                     id="inputEmail" 
                     placeholder="Email" 
                     value="{{ old('email') ?? $user->email ?? ''}}" 
                     required 
                     @isset($from) disabled @endisset>
            </div>
          </div>

          @if(!isset($from))
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-10 input-group">
                <input type="password" 
                       class="form-control" 
                       name="password" 
                       id="inputPassword" 
                       placeholder="Password" 
                       @if(!$user) required @endif>
                <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-eye"></span>
                  <span class="fas fa-eye-slash d-none"></span> 
                </div>
              </div>
              </div>
            </div>
          @endif
          
          @if(request()->segment(count(request()->segments())) != 'profile')
            <div class="form-group row">
              <label for="role" class="col-sm-2 col-form-label">Role</label>
              <div class="col-sm-10">
                <select name="role[]" id="role" class="select2bs4" 
                        multiple="multiple" 
                        data-placeholder="Choose Roles" 
                        style="width: 100%;"
                        @isset($from) disabled @endisset>
                  @forelse ($roles as $role)
                    <option value="{{$role->name}}" @if($user->hasRole($role->name)) selected @endif>{{Str::title($role->name)}}</option>
                  @empty
                    <option disabled>No Roles Available</option>
                  @endforelse
                </select>
              </div>
            </div>
            
          @endif          

          @if(!isset($from))
          <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
              @include('buttons.submit')
            </div>
          </div>
          @endif
          
        </div>
      </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->        
</div>
<!-- /.row -->