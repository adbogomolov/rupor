# sass 3.2.9
# compass 0.12.2
# https://github.com/aaronrussell/compass-rgbapng

# Require any additional compass plugins here.
require "rgbapng"

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "css"
sass_dir = "sass"
images_dir = "img"
javascripts_dir = "js"
output_style = :compact
line_comments = false

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
# line_comments = false


# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

# http://sass-lang.com/docs/yardoc/Sass/Script/Functions.html
module Sass::Script::Functions
    def getRandomColor()
        Sass::Script::String.new("#%06x" % (rand * 0xffffff))
    end
end

module Sass::Script::Functions
    def getRandomString()
        Sass::Script::String.new("%06x%06x%06x" % [(rand * 0xffffff) , (rand * 0xffffff) , (rand * 0xffffff)])
    end
end

module Sass::Script::Functions
  def reverse(string)
    assert_type string, :String
    Sass::Script::String.new(string.value.reverse)
  end
  declare :reverse, :args => [:string]
end
