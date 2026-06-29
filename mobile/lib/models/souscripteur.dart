class Souscripteur {
  final int id;
  final String uid;
  final String firstName;
  final String lastName;
  final String fullName;
  final String? email;
  final String? phone;
  final String? address;
  final String? photoUrl;
  final double fraisOuverture;
  final bool fraisOuverturePayes;
  final String? fraisRecuUrl;
  final int notificationsNonLues;

  Souscripteur({
    required this.id,
    required this.uid,
    required this.firstName,
    required this.lastName,
    required this.fullName,
    this.email,
    this.phone,
    this.address,
    this.photoUrl,
    this.fraisOuverture = 0,
    this.fraisOuverturePayes = false,
    this.fraisRecuUrl,
    this.notificationsNonLues = 0,
  });

  factory Souscripteur.fromJson(Map<String, dynamic> json) => Souscripteur(
        id: json['id'],
        uid: json['uid'] ?? '',
        firstName: json['first_name'] ?? '',
        lastName: json['last_name'] ?? '',
        fullName: json['full_name'] ?? '',
        email: json['email'],
        phone: json['phone'],
        address: json['address'],
        photoUrl: json['photo_url'],
        fraisOuverture: (json['frais_ouverture'] as num?)?.toDouble() ?? 0,
        fraisOuverturePayes: json['frais_ouverture_payes'] ?? false,
        fraisRecuUrl: json['frais_recu_url'],
        notificationsNonLues: (json['notifications_non_lues'] ?? 0) as int,
      );

  String get initials {
    final f = firstName.isNotEmpty ? firstName[0] : '';
    final l = lastName.isNotEmpty ? lastName[0] : '';
    return (f + l).toUpperCase();
  }
}
