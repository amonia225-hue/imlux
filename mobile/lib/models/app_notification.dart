class AppNotification {
  final int id;
  final String type;
  final String title;
  final String body;
  final bool read;
  final String dateDisplay;

  AppNotification({
    required this.id,
    required this.type,
    required this.title,
    required this.body,
    required this.read,
    required this.dateDisplay,
  });

  factory AppNotification.fromJson(Map<String, dynamic> j) => AppNotification(
        id: j['id'],
        type: j['type'] ?? '',
        title: j['title'] ?? '',
        body: j['body'] ?? '',
        read: j['read'] ?? false,
        dateDisplay: j['date_display'] ?? '',
      );

  String get icon => switch (type) {
        'versement' => '💰',
        'frais' => '📁',
        'travaux' => '🏗️',
        'echeance' => '⏰',
        _ => '🔔',
      };
}
