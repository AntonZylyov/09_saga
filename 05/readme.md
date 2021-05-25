# �������� � ����� �������������� ��������

��� �������� ����������� ������������ ���������� ����. 

����� �������������� �������� ��� �������������� � ������� ������ �� ����������:

![����� �������������� ��������](images/apigateway.png) 

# ��������� � ������ ����������

���� ���������� myapp ���, ������� ���:

```
kubectl create namespace myapp
```

���� ���������� ingress �� �����������, ���� ������������ ���:

```
minikube addons enable ingress
```

���������� ���������� �� helm:

```
helm install mysuperapp ./helm -n myapp
```

������ ���������� �������� ���: http://bit.homework/users/me

����������� ���: http://bit.homework/auth/register

����������� ���: http://bit.homework/auth/login
 

# ����� � ��������

�������� � ��������. �c������� [���](postman/collection.json).
�������� ��� ��� ��� �� ����� �� ��������� ���:

![Postman collection runner](images/postman.png)

 
