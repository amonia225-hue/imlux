class ProgrammeBrief {
  final int id;
  final String name;
  final String location;
  final int avancementGlobal;

  ProgrammeBrief({
    required this.id,
    required this.name,
    required this.location,
    required this.avancementGlobal,
  });

  factory ProgrammeBrief.fromJson(Map<String, dynamic> j) => ProgrammeBrief(
        id: j['id'],
        name: j['name'] ?? '',
        location: j['location'] ?? '',
        avancementGlobal: (j['avancement_global'] ?? 0) as int,
      );
}

class LotBrief {
  final String reference;
  final String typeLogement;
  final double? surface;

  LotBrief({required this.reference, required this.typeLogement, this.surface});

  factory LotBrief.fromJson(Map<String, dynamic> j) => LotBrief(
        reference: j['reference'] ?? '',
        typeLogement: j['type_logement'] ?? '',
        surface: (j['surface'] as num?)?.toDouble(),
      );
}

class Versement {
  final int id;
  final double amount;
  final String paymentDate;
  final String paymentMethodLabel;
  final String? reference;
  final String? note;
  final String factureUrl;

  Versement({
    required this.id,
    required this.amount,
    required this.paymentDate,
    required this.paymentMethodLabel,
    this.reference,
    this.note,
    required this.factureUrl,
  });

  factory Versement.fromJson(Map<String, dynamic> j) => Versement(
        id: j['id'],
        amount: (j['amount'] as num).toDouble(),
        paymentDate: j['payment_date'] ?? '',
        paymentMethodLabel: j['payment_method_label'] ?? '',
        reference: j['reference'],
        note: j['note'],
        factureUrl: j['facture_url'] ?? '',
      );
}

class Etape {
  final int id;
  final String title;
  final String? description;
  final int progress;
  final String status;
  final String statusLabel;
  final String? datePrevue;
  final String? dateRealisee;
  final String? photoUrl;
  final List<String> images;

  Etape({
    required this.id,
    required this.title,
    this.description,
    required this.progress,
    required this.status,
    required this.statusLabel,
    this.datePrevue,
    this.dateRealisee,
    this.photoUrl,
    this.images = const [],
  });

  factory Etape.fromJson(Map<String, dynamic> j) => Etape(
        id: j['id'],
        title: j['title'] ?? '',
        description: j['description'],
        progress: (j['progress'] ?? 0) as int,
        status: j['status'] ?? 'a_venir',
        statusLabel: j['status_label'] ?? '',
        datePrevue: j['date_prevue'],
        dateRealisee: j['date_realisee'],
        photoUrl: j['photo_url'],
        images: ((j['images'] ?? []) as List).map((e) => e.toString()).toList(),
      );
}

class Travaux {
  final int avancementGlobal;
  final List<Etape> etapes;

  Travaux({required this.avancementGlobal, required this.etapes});

  factory Travaux.fromJson(Map<String, dynamic> j) => Travaux(
        avancementGlobal: (j['avancement_global'] ?? 0) as int,
        etapes: ((j['etapes'] ?? []) as List)
            .map((e) => Etape.fromJson(e))
            .toList(),
      );
}

class Souscription {
  final int id;
  final ProgrammeBrief programme;
  final LotBrief lot;
  final double totalPrice;
  final double totalVerse;
  final double resteAPayer;
  final double mensualite;
  final int nbMensualites;
  final String rythmeLabel;
  final String echeanceLabel;
  final double echeanceActuelle;
  final int echeancesRestantes;
  final double progressPercent;
  final String status;
  final String dateSouscription;

  // Présents uniquement dans le détail
  final List<Versement> versements;
  final String? attestationUrl;
  final Travaux? travaux;

  Souscription({
    required this.id,
    required this.programme,
    required this.lot,
    required this.totalPrice,
    required this.totalVerse,
    required this.resteAPayer,
    required this.mensualite,
    required this.nbMensualites,
    this.rythmeLabel = 'Mensuel',
    this.echeanceLabel = 'Mensualité',
    this.echeanceActuelle = 0,
    this.echeancesRestantes = 0,
    required this.progressPercent,
    required this.status,
    required this.dateSouscription,
    this.versements = const [],
    this.attestationUrl,
    this.travaux,
  });

  factory Souscription.fromJson(Map<String, dynamic> j) => Souscription(
        id: j['id'],
        programme: ProgrammeBrief.fromJson(j['programme']),
        lot: LotBrief.fromJson(j['lot']),
        totalPrice: (j['total_price'] as num).toDouble(),
        totalVerse: (j['total_verse'] as num).toDouble(),
        resteAPayer: (j['reste_a_payer'] as num).toDouble(),
        mensualite: (j['mensualite'] as num).toDouble(),
        nbMensualites: (j['nb_mensualites'] ?? 0) as int,
        rythmeLabel: j['rythme_label'] ?? 'Mensuel',
        echeanceLabel: j['echeance_label'] ?? 'Mensualité',
        echeanceActuelle: (j['echeance_actuelle'] as num?)?.toDouble() ?? 0,
        echeancesRestantes: (j['echeances_restantes'] ?? 0) as int,
        progressPercent: (j['progress_percent'] as num).toDouble(),
        status: j['status'] ?? 'en_cours',
        dateSouscription: j['date_souscription'] ?? '',
        versements: ((j['versements'] ?? []) as List)
            .map((v) => Versement.fromJson(v))
            .toList(),
        attestationUrl: j['attestation_url'],
        travaux: j['travaux'] != null ? Travaux.fromJson(j['travaux']) : null,
      );

  String get statusLabel => switch (status) {
        'solde' => 'Soldé',
        'annule' => 'Annulé',
        _ => 'En cours',
      };
}
