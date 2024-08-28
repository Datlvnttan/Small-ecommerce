<center>
    <h1>{{$data['title']}}</h1>
    <p>Thank you for ordering from our store</p>
    <h5>This email is to notify that:</h5>
    <h4>Order '{{$data['orderKey']}}' has been {{$data['status']}}</h4>
    <h5>At: {{$data['at']}}</h5>
    <h5>Reason: {{$data['reason']}}</h5>
    <p>We will notify you of your order status when there is new information</p>
    <br>
    <br>
    <br>
    <span>If you have not yet placed an order, please ignore this email</span>
</center>